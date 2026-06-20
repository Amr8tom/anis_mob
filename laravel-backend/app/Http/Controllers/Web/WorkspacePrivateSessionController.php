<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceWalkIn;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ZipArchive;

final class WorkspacePrivateSessionController extends Controller
{
    public function index(): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $sessions = WorkspacePrivateSession::query()
            ->with('attendees')
            ->where('workspace_id', $workspace->id)
            ->latest('starts_at')
            ->paginate(12);

        return view('workspace.private-sessions.index', compact('workspace', 'sessions'));
    }

    public function create(): View
    {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.private-sessions.create', compact('workspace'));
    }

    public function store(Request $request): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'host_name' => ['nullable', 'string', 'max:160'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'price_pounds' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $session = WorkspacePrivateSession::create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'host_name' => $validated['host_name'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price_cents' => (int) round(((float) $validated['price_pounds']) * 100),
            'status' => 'active',
            'qr_token' => Str::random(48),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('workspace.private-sessions.show', $session)
            ->with('success', 'تم إنشاء الجلسة الخاصة.');
    }

    public function show(string $privateSession): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $session = $this->findSession($privateSession, $workspace->id)
            ->load(['attendees' => fn ($query) => $query->latest('created_at')]);

        return view('workspace.private-sessions.show', [
            'workspace' => $workspace,
            'session' => $session,
            'summary' => $session->summary(),
            'checkInUrl' => url('/private-session/check-in/'.$session->qr_token),
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
            'attendees_file' => ['required', 'file', 'max:5120'],
        ]);

        $added = $duplicates = $failed = 0;
        $errors = [];
        $rowNumber = 0;

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

            $result = $this->addAttendee($session, $phone, $name !== '' ? $name : null, 'excel');

            if ($result['status'] === 'added') {
                $added++;
            } elseif ($result['status'] === 'duplicate') {
                $duplicates++;
            } else {
                $failed++;
                $errors[] = "صف {$rowNumber}: {$result['message']}";
            }
        }

        return back()->with('success', "تم الاستيراد: {$added} مضاف، {$duplicates} مكرر، {$failed} فشل.")
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

        $this->markAttended($model, 'owner', (string) Auth::id());

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

        $user = User::query()->whereRaw($this->phoneNormalizeSql('phone_number').' = ?', [$normalized])->first();
        $walkIn = null;
        $displayName = $user?->full_name ?? trim((string) $name);

        if ($user === null) {
            if ($displayName === '') {
                return ['status' => 'failed', 'message' => 'الاسم مطلوب إذا كان الرقم غير مسجل في التطبيق.'];
            }

            $walkIn = WorkspaceWalkIn::query()
                ->where('workspace_id', $session->workspace_id)
                ->whereRaw($this->phoneNormalizeSql('phone_number').' = ?', [$normalized])
                ->first();

            if ($walkIn === null) {
                $walkIn = WorkspaceWalkIn::create([
                    'workspace_id' => $session->workspace_id,
                    'full_name' => $displayName,
                    'phone_number' => $phone,
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
                'checked_in_by_owner_id' => $checkInNow ? Auth::id() : null,
                'amount_cents' => (int) $session->price_cents,
                'payment_status' => 'paid',
            ]);
        } catch (QueryException) {
            return ['status' => 'duplicate', 'message' => 'هذا الرقم موجود بالفعل في الجلسة.'];
        }

        if ($checkInNow && $attendee->status !== 'attended') {
            $this->markAttended($attendee, 'owner', (string) Auth::id());
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
            'checked_in_by_owner_id' => $ownerId,
            'amount_cents' => $attendee->amount_cents > 0
                ? $attendee->amount_cents
                : (int) $attendee->privateSession()->value('price_cents'),
        ]);
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }

    private function phoneNormalizeSql(string $column): string
    {
        return "replace(replace(replace(replace({$column}, ' ', ''), '-', ''), '(', ''), ')', '')";
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

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $shared = simplexml_load_string($sharedXml);
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

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        abort_if($sheetXml === false, 422, 'Unable to read first XLSX sheet.');

        $sheet = simplexml_load_string($sheetXml);
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
}
