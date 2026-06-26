<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Notifications\Actions\ScheduleAutomaticNotificationTaskAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspaceCenterGradeLevel;
use App\Models\WorkspaceCenterSubject;
use App\Models\WorkspaceCenterTeacher;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspacePrivateSessionImportBatch;
use App\Models\WorkspacePrivateSessionImportRow;
use App\Models\WorkspaceWalkIn;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ZipArchive;

final class WorkspacePrivateSessionController extends Controller
{
    public function index(): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $filters = request()->validate([
            'teacher' => ['nullable', 'uuid'],
            'subject' => ['nullable', 'uuid'],
            'grade_level' => ['nullable', 'uuid'],
            'status' => ['nullable', Rule::in(['active', 'finished', 'cancelled'])],
        ]);
        $sessions = WorkspacePrivateSession::query()
            ->with(['centerTeacher', 'centerSubject', 'centerGradeLevel'])
            ->where('workspace_id', $workspace->id)
            ->when($filters['teacher'] ?? null, fn ($query, $id) => $query->where('center_teacher_id', $id))
            ->when($filters['subject'] ?? null, fn ($query, $id) => $query->where('center_subject_id', $id))
            ->when($filters['grade_level'] ?? null, fn ($query, $id) => $query->where('center_grade_level_id', $id))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('workspace.private-sessions.index', [
            'workspace' => $workspace,
            'sessions' => $sessions,
            'teachers' => $this->activeTeachers($workspace->id),
            'subjects' => $this->activeSubjects($workspace->id),
            'gradeLevels' => $this->activeGradeLevels($workspace->id),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.private-sessions.create', [
            'workspace' => $workspace,
            'teachers' => $this->activeTeachers($workspace->id),
            'subjects' => $this->activeSubjects($workspace->id),
            'gradeLevels' => $this->activeGradeLevels($workspace->id),
        ]);
    }

    public function store(Request $request, ScheduleAutomaticNotificationTaskAction $scheduledNotifications): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'host_name' => ['nullable', 'string', 'max:160'],
            'use_education_data' => ['nullable', 'boolean'],
            'center_teacher_id' => [
                'nullable',
                Rule::exists('workspace_center_teachers', 'id')->where('workspace_id', $workspace->id),
            ],
            'center_subject_id' => [
                'nullable',
                Rule::exists('workspace_center_subjects', 'id')->where('workspace_id', $workspace->id),
            ],
            'center_grade_level_id' => [
                'nullable',
                Rule::exists('workspace_center_grade_levels', 'id')->where('workspace_id', $workspace->id),
            ],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'price_pounds' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'instructor_payout_type' => ['required', Rule::in(['none', 'percentage', 'per_attendee_fixed', 'session_fixed'])],
            'instructor_payout_value' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $useEducationData = (bool) ($validated['use_education_data'] ?? false);

        if (! $useEducationData) {
            $validated['center_teacher_id'] = null;
            $validated['center_subject_id'] = null;
            $validated['center_grade_level_id'] = null;
        }

        $payoutValue = $this->normalizePayoutValue(
            $validated['instructor_payout_type'],
            isset($validated['instructor_payout_value']) ? (float) $validated['instructor_payout_value'] : 0.0,
        );

        $session = WorkspacePrivateSession::create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => null,
            'created_by_workspace_owner_id' => $this->currentWorkspaceOwnerId(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'host_name' => $validated['host_name'] ?? null,
            'center_teacher_id' => $validated['center_teacher_id'] ?? null,
            'center_subject_id' => $validated['center_subject_id'] ?? null,
            'center_grade_level_id' => $validated['center_grade_level_id'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price_cents' => (int) round(((float) $validated['price_pounds']) * 100),
            'instructor_payout_type' => $validated['instructor_payout_type'],
            'instructor_payout_value' => $payoutValue,
            'status' => 'active',
            'qr_token' => Str::random(48),
            'notes' => $validated['notes'] ?? null,
        ]);

        $scheduledNotifications->privateSessionReminder($session);

        return redirect()
            ->route('workspace.private-sessions.show', $session)
            ->with('success', 'تم إنشاء الجلسة الخاصة.');
    }

    public function show(string $privateSession): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $search = trim((string) request('attendee_search', ''));
        $normalizedSearch = $this->normalizePhone($search);
        $attendeesQuery = $session->attendees()
            ->when($search !== '', function ($query) use ($search, $normalizedSearch): void {
                $query->where(function ($query) use ($search, $normalizedSearch): void {
                    $query->where('name_snapshot', 'like', $search.'%');

                    if ($normalizedSearch !== '') {
                        $query->orWhere('phone_normalized', 'like', $normalizedSearch.'%');
                    }
                });
            })
            ->latest('created_at');
        $attendees = (clone $attendeesQuery)
            ->paginate(25, ['*'], 'attendees_page')
            ->withQueryString();
        $attendedRows = $session->attendees()
            ->where('status', 'attended')
            ->latest('checked_in_at')
            ->paginate(15, ['*'], 'attended_page')
            ->withQueryString();
        $importBatch = WorkspacePrivateSessionImportBatch::query()
            ->where('workspace_private_session_id', $session->id)
            ->where('status', 'preview')
            ->latest()
            ->first();
        $importRows = $importBatch
            ? $importBatch->rows()->orderBy('row_number')->paginate(50, ['*'], 'import_rows_page')->withQueryString()
            : null;

        return view('workspace.private-sessions.show', [
            'workspace' => $workspace,
            'session' => $session,
            'summary' => $session->summary(),
            'checkInUrl' => url('/private-session/check-in/'.$session->qr_token),
            'attendees' => $attendees,
            'attendedRows' => $attendedRows,
            'attendeeSearch' => $search,
            'importBatch' => $importBatch,
            'importRows' => $importRows,
        ]);
    }

    public function qr(string $privateSession): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $checkInUrl = url('/private-session/check-in/'.$session->qr_token);

        return view('workspace.private-sessions.qr', compact('workspace', 'session', 'checkInUrl'));
    }

    public function storeAttendee(Request $request, string $privateSession): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:30'],
            'name' => ['nullable', 'string', 'max:160'],
            'check_in_now' => ['nullable', 'boolean'],
        ]);

        $result = $this->addAttendee(
            $session,
            $validated['phone_number'],
            $validated['name'] ?? null,
            'manual',
            (bool) ($validated['check_in_now'] ?? false),
        );

        if ($result['status'] === 'failed') {
            return back()->withErrors(['phone_number' => $result['message']])->withInput();
        }

        return back()->with('success', $result['message']);
    }

    public function importAttendees(Request $request, string $privateSession): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $validated = $request->validate([
            // Restrict to spreadsheet/text MIME types — without this, any file
            // type could be uploaded (client-supplied extension is spoofable).
            'attendees_file' => ['required', 'file', 'max:5120', 'mimes:xlsx,csv,txt'],
        ]);

        $summary = ['valid' => 0, 'duplicates' => 0, 'failed' => 0];
        $rowNumber = 0;
        $rows = [];

        foreach ($this->readImportRows($validated['attendees_file']) as $row) {
            $rowNumber++;
            if ($rowNumber === 1 && $this->looksLikeHeader($row)) {
                continue;
            }

            $phone = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));

            if ($phone === '' && $name !== '') {
                $phone = $name;
                $name = trim((string) ($row[0] ?? ''));
            }

            $preview = $this->previewImportRow($session, $rowNumber, $phone, $name !== '' ? $name : null);
            $rows[] = $preview;

            $summary[$preview['status'] === 'valid' ? 'valid' : $preview['status']]++;
        }

        DB::transaction(function () use ($session, $request, $validated, $summary, $rows): void {
            WorkspacePrivateSessionImportBatch::query()
                ->where('workspace_private_session_id', $session->id)
                ->where('status', 'preview')
                ->update(['status' => 'replaced']);

            $batch = WorkspacePrivateSessionImportBatch::create([
                'workspace_private_session_id' => $session->id,
                'workspace_id' => $session->workspace_id,
                'created_by_owner_id' => null,
                'created_by_workspace_owner_id' => $this->currentWorkspaceOwnerId(),
                'original_filename' => $validated['attendees_file']->getClientOriginalName(),
                'status' => 'preview',
                'valid_count' => $summary['valid'],
                'duplicate_count' => $summary['duplicates'],
                'failed_count' => $summary['failed'],
            ]);

            foreach (array_chunk($rows, 500) as $chunk) {
                WorkspacePrivateSessionImportRow::insert(array_map(fn (array $row): array => [
                    'id' => (string) Str::uuid(),
                    'workspace_private_session_import_batch_id' => $batch->id,
                    'workspace_private_session_id' => $session->id,
                    'workspace_id' => $session->workspace_id,
                    'row_number' => $row['row_number'],
                    'phone_snapshot' => $row['phone'],
                    'phone_normalized' => $row['normalized_phone'],
                    'name_snapshot' => $row['name'],
                    'resolved_name' => $row['resolved_name'],
                    'visitor_type' => $row['visitor_type'],
                    'status' => $row['status'],
                    'message' => $row['message'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $chunk));
            }
        });

        return back()->with('success', "تم تجهيز المعاينة: {$summary['valid']} صالح، {$summary['duplicates']} مكرر، {$summary['failed']} يحتاج مراجعة.");
    }

    public function confirmImportAttendees(Request $request, string $privateSession): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $batch = WorkspacePrivateSessionImportBatch::query()
            ->where('workspace_private_session_id', $session->id)
            ->where('workspace_id', $workspace->id)
            ->where('status', 'preview')
            ->latest()
            ->first();

        if ($batch === null) {
            return back()->withErrors(['attendees_file' => 'لا توجد معاينة جاهزة للحفظ. ارفع الملف مرة أخرى.']);
        }

        $added = $duplicates = $failed = 0;
        $errors = [];

        $batch->rows()
            ->where('status', 'valid')
            ->chunkById(500, function ($rows) use ($session, &$added, &$duplicates, &$failed, &$errors): void {
                foreach ($rows as $row) {
                    $result = $this->addAttendee($session, (string) $row->phone_snapshot, $row->resolved_name ?? $row->name_snapshot, 'excel');

                    if ($result['status'] === 'added') {
                        $added++;
                    } elseif ($result['status'] === 'duplicate') {
                        $duplicates++;
                    } else {
                        $failed++;
                        $errors[] = 'صف '.$row->row_number.': '.$result['message'];
                    }
                }
            });

        $batch->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return back()
            ->with('success', "تم حفظ الاستيراد: {$added} مضاف، {$duplicates} مكرر، {$failed} فشل.")
            ->with('import_errors', array_slice($errors, 0, 8));
    }

    public function checkInAttendee(string $privateSession, string $attendee): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id);
        $model = $session->attendees()
            ->where('workspace_id', $workspace->id)
            ->where('id', $attendee)
            ->firstOrFail();

            $this->markAttended($model, 'owner', $this->currentWorkspaceOwnerId());

        return back()->with('success', 'تم تسجيل حضور الزائر.');
    }

    public function finish(string $privateSession): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $this->findSession($privateSession, $workspace->id)->update(['status' => 'finished']);

        return back()->with('success', 'تم إنهاء الجلسة.');
    }

    public function cancel(string $privateSession): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $this->findSession($privateSession, $workspace->id)->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الجلسة.');
    }

    private function findSession(string $id, string $workspaceId): WorkspacePrivateSession
    {
        return WorkspacePrivateSession::query()
            ->where('workspace_id', $workspaceId)
            ->where('id', $id)
            ->firstOrFail();
    }

    private function normalizePayoutValue(string $type, float $value): int
    {
        return match ($type) {
            'percentage' => (int) round($value * 100),
            'per_attendee_fixed', 'session_fixed' => (int) round($value * 100),
            default => 0,
        };
    }

    private function activeTeachers(string $workspaceId)
    {
        return WorkspaceCenterTeacher::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function activeSubjects(string $workspaceId)
    {
        return WorkspaceCenterSubject::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function activeGradeLevels(string $workspaceId)
    {
        return WorkspaceCenterGradeLevel::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array{status:string,message:string}
     */
    private function addAttendee(
        WorkspacePrivateSession $session,
        string $phone,
        ?string $name,
        string $source,
        bool $checkInNow = false,
    ): array {
        $normalized = $this->normalizePhone($phone);
        if ($normalized === '') {
            return ['status' => 'failed', 'message' => 'رقم الهاتف غير صالح.'];
        }

        $user = User::query()->where('phone_number_normalized', $normalized)->first();
        $walkIn = null;
        $displayName = $user?->full_name ?? trim((string) $name);

        if ($user === null) {
            if ($displayName === '') {
                return ['status' => 'failed', 'message' => 'الاسم مطلوب إذا كان الرقم غير مسجل في التطبيق.'];
            }

            $walkIn = WorkspaceWalkIn::query()
                ->where('workspace_id', $session->workspace_id)
                ->where('phone_number_normalized', $normalized)
                ->first();

            if ($walkIn === null) {
                $walkIn = WorkspaceWalkIn::create([
                    'workspace_id' => $session->workspace_id,
                    'full_name' => $displayName,
                    'phone_number' => $phone,
                    'phone_number_normalized' => $normalized,
                ]);
            }
        }

        try {
            $attendee = WorkspacePrivateSessionAttendee::create([
                'workspace_private_session_id' => $session->id,
                'workspace_id' => $session->workspace_id,
                'user_id' => $user?->id,
                'walk_in_id' => $walkIn?->id,
                'name_snapshot' => $displayName,
                'phone_snapshot' => $user?->phone_number ?? $phone,
                'phone_normalized' => $normalized,
                'source' => $source,
                'status' => $checkInNow ? 'attended' : 'invited',
                'checked_in_at' => $checkInNow ? now() : null,
                'checked_in_method' => $checkInNow ? 'owner' : null,
                'checked_in_by_owner_id' => null,
                'checked_in_by_workspace_owner_id' => $checkInNow ? $this->currentWorkspaceOwnerId() : null,
                'amount_cents' => (int) $session->price_cents,
                'payment_status' => 'paid',
            ]);
        } catch (QueryException) {
            return ['status' => 'duplicate', 'message' => 'هذا الرقم موجود بالفعل في الجلسة.'];
        }

        if ($checkInNow && $attendee->status !== 'attended') {
            $this->markAttended($attendee, 'owner', $this->currentWorkspaceOwnerId());
        }

        return ['status' => 'added', 'message' => $checkInNow ? 'تم إضافة الزائر وتسجيل حضوره.' : 'تم إضافة الزائر للجلسة.'];
    }

    private function markAttended(WorkspacePrivateSessionAttendee $attendee, string $method, ?string $ownerId = null): void
    {
        if ($attendee->status === 'attended') {
            return;
        }

        $attendee->update([
            'status' => 'attended',
            'checked_in_at' => now(),
            'checked_in_method' => $method,
            'checked_in_by_owner_id' => null,
            'checked_in_by_workspace_owner_id' => $ownerId,
            'amount_cents' => $attendee->amount_cents > 0
                ? $attendee->amount_cents
                : (int) $attendee->privateSession()->value('price_cents'),
        ]);
    }

    private function currentWorkspaceOwnerId(): ?string
    {
        return Auth::guard('workspace_owner')->check()
            ? (string) Auth::guard('workspace_owner')->id()
            : null;
    }

    /**
     * @return array{
     *     row_number:int,
     *     phone:string,
     *     name:?string,
     *     normalized_phone:string,
     *     resolved_name:?string,
     *     visitor_type:string,
     *     status:string,
     *     message:string
     * }
     */
    private function previewImportRow(WorkspacePrivateSession $session, int $rowNumber, string $phone, ?string $name): array
    {
        $normalized = $this->normalizePhone($phone);
        $base = [
            'row_number' => $rowNumber,
            'phone' => $phone,
            'name' => $name,
            'normalized_phone' => $normalized,
            'resolved_name' => null,
            'visitor_type' => '—',
            'status' => 'failed',
            'message' => '',
        ];

        if ($normalized === '') {
            return [...$base, 'message' => 'رقم الهاتف غير صالح.'];
        }

        $alreadyAdded = WorkspacePrivateSessionAttendee::query()
            ->where('workspace_private_session_id', $session->id)
            ->where('phone_normalized', $normalized)
            ->exists();

        if ($alreadyAdded) {
            return [
                ...$base,
                'status' => 'duplicates',
                'message' => 'هذا الرقم موجود بالفعل في الجلسة.',
            ];
        }

        $user = User::query()
            ->where('phone_number_normalized', $normalized)
            ->first();

        if ($user !== null) {
            return [
                ...$base,
                'phone' => $user->phone_number,
                'resolved_name' => $user->full_name,
                'visitor_type' => 'مستخدم تطبيق',
                'status' => 'valid',
                'message' => 'جاهز للإضافة كمستخدم تطبيق.',
            ];
        }

        $displayName = trim((string) $name);
        if ($displayName === '') {
            return [
                ...$base,
                'visitor_type' => 'زائر مباشر',
                'message' => 'الاسم مطلوب إذا كان الرقم غير مسجل في التطبيق.',
            ];
        }

        return [
            ...$base,
            'resolved_name' => $displayName,
            'visitor_type' => 'زائر مباشر',
            'status' => 'valid',
            'message' => 'جاهز للإضافة كزائر مباشر.',
        ];
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }

    /**
     * @param  array<int, string|null>  $row
     */
    private function looksLikeHeader(array $row): bool
    {
        $first = strtolower(trim((string) ($row[0] ?? '')));
        $second = strtolower(trim((string) ($row[1] ?? '')));

        return str_contains($first, 'phone')
            || str_contains($first, 'رقم')
            || str_contains($second, 'name')
            || str_contains($second, 'اسم');
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    private function readImportRows(object $file): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if ($extension === 'xlsx') {
            return $this->readXlsxRows((string) $file->getRealPath());
        }

        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');
        abort_if($handle === false, 422);

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Minimal XLSX reader for the first sheet, enough for owner imports with
     * columns A=phone and B=name. This avoids adding a package for a small flow.
     *
     * @return array<int, array<int, string|null>>
     */
    private function readXlsxRows(string $path): array
    {
        abort_unless(class_exists(ZipArchive::class), 422, 'XLSX import is not available on this server.');

        $zip = new ZipArchive;
        abort_unless($zip->open($path) === true, 422, 'Unable to read XLSX file.');

        // SECURITY: an "xlsx" is just a ZIP of attacker-controlled XML. Cap the
        // decompressed size of each member we read to guard against zip-bomb
        // style DoS before we ever hand the string to the XML parser.
        $maxXmlBytes = 5 * 1024 * 1024; // 5MB decompressed, per file

        $sharedStrings = [];
        $sharedXml = $this->readZipEntryCapped($zip, 'xl/sharedStrings.xml', $maxXmlBytes);
        if ($sharedXml !== false) {
            $shared = $this->parseXmlSafely($sharedXml);
            if ($shared !== false) {
                foreach ($shared->xpath('//*[local-name()="si"]') ?: [] as $si) {
                    $parts = [];
                    foreach ($si->xpath('.//*[local-name()="t"]') ?: [] as $text) {
                        $parts[] = (string) $text;
                    }
                    $sharedStrings[] = implode('', $parts);
                }
            }
        }

        $sheetXml = $this->readZipEntryCapped($zip, 'xl/worksheets/sheet1.xml', $maxXmlBytes);
        $zip->close();
        abort_if($sheetXml === false, 422, 'Unable to read first XLSX sheet.');

        $sheet = $this->parseXmlSafely($sheetXml);
        abort_if($sheet === false, 422, 'Invalid XLSX sheet.');

        $rows = [];
        foreach ($sheet->xpath('//*[local-name()="row"]') ?: [] as $row) {
            $values = [];
            foreach ($row->xpath('./*[local-name()="c"]') ?: [] as $cell) {
                $reference = (string) ($cell['r'] ?? '');
                $column = preg_replace('/\d+/', '', $reference) ?: '';
                $index = $this->columnIndex($column);
                if ($index > 1) {
                    continue;
                }

                $value = (string) (($cell->xpath('./*[local-name()="v"]')[0] ?? null) ?: '');
                if ((string) ($cell['t'] ?? '') === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                }
                $values[$index] = $value;
            }

            if ($values !== []) {
                ksort($values);
                $rows[] = [$values[0] ?? null, $values[1] ?? null];
            }
        }

        return $rows;
    }

    private function columnIndex(string $column): int
    {
        $column = strtoupper($column);
        $index = 0;

        foreach (str_split($column) as $character) {
            $index = $index * 26 + (ord($character) - 64);
        }

        return max(0, $index - 1);
    }

    /**
     * Read a single entry from the zip, rejecting it outright if its
     * (uncompressed) size exceeds $maxBytes. Defends against zip-bomb style
     * decompression DoS from an attacker-supplied "xlsx" file.
     *
     * @return string|false
     */
    private function readZipEntryCapped(ZipArchive $zip, string $entryName, int $maxBytes)
    {
        $index = $zip->locateName($entryName);
        if ($index === false) {
            return false;
        }

        $stat = $zip->statIndex($index);
        if ($stat === false || ($stat['size'] ?? 0) > $maxBytes) {
            return false;
        }

        return $zip->getFromName($entryName);
    }

    /**
     * Parse XML with external entity loading / DTDs disabled to prevent XXE
     * (an attacker fully controls the XML inside an uploaded "xlsx" file).
     *
     * Deliberately does NOT pass LIBXML_NOENT or LIBXML_DTDLOAD — those would
     * enable entity substitution / DTD processing, which is exactly the XXE /
     * "billion laughs" attack surface we're closing off. LIBXML_NONET blocks
     * any attempt to resolve entities over the network as a defense in depth.
     *
     * @return \SimpleXMLElement|false
     */
    private function parseXmlSafely(string $xml)
    {
        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NONET);
    }
}
