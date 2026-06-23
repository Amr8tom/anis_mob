<?php

namespace App\Http\Controllers\Web;

use App\Domain\Admin\Actions\LogAdminAction;
use App\Domain\WorkspaceSettlement\Actions\GetWorkspaceVisitReportAction;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceLifecycleStatus;
use App\Enums\WorkspaceStatus;
use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use App\Models\WorkspaceOwnershipChange;
use App\Models\WorkspaceOwnershipInvitation;
use App\Models\WorkspaceVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminWorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $trashed = $request->boolean('trashed');

        $workspaces = Workspace::query()
            ->when($trashed, fn ($q) => $q->onlyTrashed())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Registrations awaiting review are surfaced at the top of the page.
        $pendingWorkspaces = Workspace::query()
            ->with(['owner', 'workspaceOwner'])
            ->where('lifecycle_status', WorkspaceLifecycleStatus::PENDING->value)
            ->latest()
            ->get();

        return view('admin.workspaces.index', compact('workspaces', 'pendingWorkspaces', 'trashed'));
    }

    public function create(): View
    {
        return view('admin.workspaces.create');
    }

    public function store(Request $request, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'admin_phone' => ['nullable', 'string', 'max:30'],
            'owner_phone' => ['required', 'string', 'max:30'],
        ]);

        $ownerPhone = $validated['owner_phone'];
        unset($validated['owner_phone']);
        [$workspace, $invitationToken] = DB::transaction(function () use ($validated, $ownerPhone): array {
            $owner = WorkspaceOwner::query()
                ->where('phone_number', $ownerPhone)
                ->orWhere('phone_number_normalized', WorkspaceOwner::normalizePhone($ownerPhone))
                ->first();
            if ($owner !== null) {
                abort_if($owner->ownedWorkspace()->exists(), 422, 'This owner already has a workspace.');
            }

            $workspace = Workspace::create([
                ...$validated,
                'workspace_owner_id' => $owner?->id,
                'qr_token' => (string) Str::uuid7(),
                'status' => WorkspaceStatus::OPEN,
                'is_active' => true,
            ]);

            if ($owner !== null) {
                WorkspaceOwnershipChange::create([
                    'workspace_id' => $workspace->id,
                    'new_owner_id' => null,
                    'changed_by_admin_id' => (string) Auth::id(),
                    'reason' => 'Assigned WorkspaceOwner during admin workspace creation: '.$owner->id,
                ]);

                return [$workspace, null];
            }

            $token = Str::random(48);
            WorkspaceOwnershipInvitation::create([
                'workspace_id' => $workspace->id,
                'phone_number' => $ownerPhone,
                'token_hash' => hash('sha256', $token),
                'invited_by_admin_id' => (string) Auth::id(),
                'expires_at' => now()->addDays(7),
            ]);

            return [$workspace, $token];
        });

        $logger->handle(
            adminId: (string) Auth::id(),
            action: 'CREATE_WORKSPACE',
            entityType: 'Workspace',
            entityId: $workspace->id,
            ipAddress: $request->ip(),
        );

        $message = $invitationToken === null
            ? 'Workspace created and owner assigned successfully.'
            : "Workspace created. Send this one-time owner invitation token: {$invitationToken}";

        return redirect()->route('admin.workspaces.show', $workspace)->with('success', $message);
    }

    public function changeOwner(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'owner_phone' => ['required', 'string', 'exists:workspace_owners,phone_number'],
            'reason' => ['required', 'string', 'max:255'],
        ]);
        $owner = WorkspaceOwner::where('phone_number', $validated['owner_phone'])->firstOrFail();
        abort_if($owner->ownedWorkspace()->whereKeyNot($workspace->id)->exists(), 422, 'This owner already has a workspace.');

        DB::transaction(function () use ($workspace, $owner, $validated): void {
            $previousOwnerId = $workspace->owner_id;
            $workspace->update(['workspace_owner_id' => $owner->id]);
            WorkspaceOwnershipChange::create([
                'workspace_id' => $workspace->id,
                'previous_owner_id' => $previousOwnerId,
                'new_owner_id' => null,
                'changed_by_admin_id' => (string) Auth::id(),
                'reason' => $validated['reason'].' (WorkspaceOwner: '.$owner->id.')',
            ]);
        });

        $logger->handle((string) Auth::id(), 'CHANGE_WORKSPACE_OWNER', 'Workspace', $workspace->id, [
            'new_workspace_owner_id' => $owner->id,
            'reason' => $validated['reason'],
        ], $request->ip());

        return back()->with('success', 'Workspace owner changed successfully.');
    }

    public function show(Request $request, Workspace $workspace, GetWorkspaceVisitReportAction $report): View
    {
        $workspace->load(['owner', 'workspaceOwner']);

        // A pending registration has no visit history yet — show the review screen
        // (all submitted data + images) so the admin can approve or reject it.
        if ($workspace->isPending()) {
            return view('admin.workspaces.review', compact('workspace'));
        }

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'tier' => ['nullable', 'in:FREE,GLOBAL_SUBSCRIPTION,WORKSPACE_SUBSCRIPTION'],
        ]);

        $from = isset($validated['from'])
            ? Carbon::parse($validated['from'])->startOfDay()
            : now()->startOfMonth();
        $to = isset($validated['to'])
            ? Carbon::parse($validated['to'])->endOfDay()
            : now()->endOfMonth();
        $toExclusive = $to->copy()->addSecond();
        $tier = $validated['tier'] ?? null;

        $summary = $report->handle($workspace, $from, $toExclusive);

        $visits = WorkspaceVisit::query()
            ->with(['user', 'walkIn'])
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $from)
            ->where('check_out_at', '<', $toExclusive)
            ->when($tier, function ($query, string $source) {
                if ($source === 'FREE') {
                    $query->where(fn ($q) => $q->where('billing_source', 'FREE')->orWhereNull('billing_source'));
                } else {
                    $query->where('billing_source', $source);
                }
            })
            ->latest('check_out_at')
            ->paginate(25)
            ->withQueryString();

        // Delete-guard context shown next to the danger-zone controls.
        $activeVisitsCount = $workspace->visits()
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->count();
        $unsettled = $this->unsettledEarnings($workspace);

        $settlements = $workspace->settlements()->with('reversal')->latest('paid_at')->limit(10)->get();
        $latestPayableDate = now()->subDay()->endOfDay();
        $paymentFrom = $from->isAfter($latestPayableDate)
            ? $latestPayableDate->copy()->startOfMonth()
            : $from;
        $paymentTo = $to->isAfter($latestPayableDate)
            ? $latestPayableDate
            : $to;

        return view('admin.workspaces.show', compact(
            'workspace',
            'summary',
            'visits',
            'settlements',
            'from',
            'to',
            'tier',
            'latestPayableDate',
            'paymentFrom',
            'paymentTo',
            'activeVisitsCount',
            'unsettled',
        ));
    }

    /**
     * Best-effort "money we may still owe this workspace": billable visits that
     * checked out after the latest settled period. Used as a *warning* before
     * deletion, not a hard block — soft-delete preserves everything regardless.
     *
     * @return array{visits:int, hours:float, amount_egp:float}
     */
    private function unsettledEarnings(Workspace $workspace): array
    {
        $lastSettledAt = $workspace->settlements()->max('period_ended_at');

        $minutes = (int) WorkspaceVisit::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->when($lastSettledAt, fn ($q) => $q->where('check_out_at', '>', $lastSettledAt))
            ->sum('billable_minutes');

        $count = (int) WorkspaceVisit::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('billable_minutes', '>', 0)
            ->when($lastSettledAt, fn ($q) => $q->where('check_out_at', '>', $lastSettledAt))
            ->count();

        $hours = round($minutes / 60, 1);

        return [
            'visits' => $count,
            'hours' => $hours,
            'amount_egp' => round($hours * $workspace->effectiveHourlyRateEgp(), 2),
        ];
    }

    public function updateBilling(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'base_hourly_rate_egp' => ['required', 'numeric', 'min:0', 'max:100000'],
            'hour_multiplier' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:10'],
            'day_calculation_hours' => ['required', 'integer', 'min:1', 'max:24'],
        ]);

        $billingData = [
            'payout_rate_cents_per_hour' => (int) round((float) $validated['base_hourly_rate_egp'] * 100),
            'payout_currency' => 'EGP',
            'hour_multiplier' => $validated['hour_multiplier'],
            'day_calculation_hours' => $validated['day_calculation_hours'],
        ];

        $workspace->update($billingData);

        $logger->handle(
            adminId: (string) Auth::id(),
            action: 'UPDATE_WORKSPACE_BILLING',
            entityType: 'Workspace',
            entityId: $workspace->id,
            details: $billingData,
            ipAddress: $request->ip(),
        );

        return back()->with('success', 'تم تحديث إعدادات الفوترة بنجاح.');
    }

    public function regenerateQr(Workspace $workspace, LogAdminAction $logger)
    {
        $newToken = Str::random(32);

        $workspace->update(['qr_token' => $newToken]);

        $logger->handle(
            adminId: Auth::id(),
            action: 'REGENERATE_WORKSPACE_QR',
            entityType: 'Workspace',
            entityId: $workspace->id,
            details: ['token_fingerprint' => substr(hash('sha256', $newToken), 0, 12)],
            ipAddress: request()->ip(),
        );

        return redirect()->route('admin.workspaces.show', $workspace)->with('success', 'QR Code regenerated securely.');
    }

    /**
     * Approve a pending registration: the workspace goes live and becomes visible
     * on the mobile app, and the owner can sign in to the portal.
     */
    public function approve(Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        abort_unless($workspace->isPending(), 422, 'Only pending workspaces can be approved.');

        $workspace->update([
            'lifecycle_status' => WorkspaceLifecycleStatus::APPROVED,
            'is_active' => true,
            'approved_at' => now(),
            'approved_by_admin_id' => (string) Auth::id(),
        ]);

        $logger->handle((string) Auth::id(), 'APPROVE_WORKSPACE', 'Workspace', $workspace->id, null, request()->ip());

        return redirect()->route('admin.workspaces.show', $workspace)->with('success', 'تمت الموافقة على مساحة العمل وتفعيلها.');
    }

    /**
     * Reject a pending registration. A pending workspace has zero history, so we
     * hard-delete it AND its owner user — the only way to free the unique phone
     * number for a future re-registration. An audit snapshot is written first.
     */
    public function reject(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        abort_unless($workspace->isPending(), 422, 'Only pending workspaces can be rejected.');

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $owner = $workspace->workspaceOwner;

        $logger->handle(
            adminId: (string) Auth::id(),
            action: 'REJECT_WORKSPACE',
            entityType: 'Workspace',
            entityId: $workspace->id,
            details: [
                'reason' => $validated['reason'],
                'workspace_name' => $workspace->name,
                'owner_phone' => $owner?->phone_number,
                'owner_name' => $owner?->full_name,
            ],
            ipAddress: $request->ip(),
        );

        DB::transaction(function () use ($workspace, $owner): void {
            // Hard delete (not soft) so the owner's unique phone_number is freed.
            $workspace->forceDelete();
            $owner?->forceDelete();
        });

        return redirect()->route('admin.workspaces.index')->with('success', 'تم رفض التسجيل وحذف الطلب.');
    }

    /**
     * Freeze a live workspace: hidden from mobile, check-ins blocked, owner locked
     * out — fully reversible. Data is preserved.
     */
    public function suspend(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        abort_unless(
            in_array($workspace->lifecycle_status, [WorkspaceLifecycleStatus::APPROVED, WorkspaceLifecycleStatus::SUSPENDED], true),
            422,
            'Only an approved workspace can be suspended.',
        );

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $workspace->update([
            'lifecycle_status' => WorkspaceLifecycleStatus::SUSPENDED,
            'is_active' => false,
            'suspended_at' => now(),
            'suspended_by_admin_id' => (string) Auth::id(),
            'suspension_reason' => $validated['reason'] ?? null,
        ]);

        $logger->handle((string) Auth::id(), 'SUSPEND_WORKSPACE', 'Workspace', $workspace->id, [
            'reason' => $validated['reason'] ?? null,
        ], $request->ip());

        return back()->with('success', 'تم إيقاف مساحة العمل مؤقتًا.');
    }

    public function unsuspend(Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        abort_unless($workspace->isSuspended(), 422, 'Workspace is not suspended.');

        $workspace->update([
            'lifecycle_status' => WorkspaceLifecycleStatus::APPROVED,
            'is_active' => true,
            'suspended_at' => null,
            'suspended_by_admin_id' => null,
            'suspension_reason' => null,
        ]);

        $logger->handle((string) Auth::id(), 'UNSUSPEND_WORKSPACE', 'Workspace', $workspace->id, null, request()->ip());

        return back()->with('success', 'تم إعادة تفعيل مساحة العمل.');
    }

    /**
     * Direct admin control over the operational status (OPEN/BUSY/FULL/CLOSED)
     * and the active toggle — the light lever alongside suspend.
     */
    public function updateStatus(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        abort_if($workspace->isPending(), 422, 'Approve the workspace before changing its status.');

        $validated = $request->validate([
            'status' => ['required', Rule::enum(WorkspaceStatus::class)],
            'is_active' => ['required', 'boolean'],
        ]);

        // Suspension is an admin lock that overrides the operational toggle; don't
        // let a status edit silently un-suspend a frozen workspace.
        $isActive = $workspace->isSuspended() ? false : (bool) $validated['is_active'];

        $workspace->update([
            'status' => $validated['status'],
            'is_active' => $isActive,
        ]);

        $logger->handle((string) Auth::id(), 'UPDATE_WORKSPACE_STATUS', 'Workspace', $workspace->id, [
            'status' => $validated['status'],
            'is_active' => $isActive,
        ], $request->ip());

        return back()->with('success', 'تم تحديث حالة مساحة العمل.');
    }

    /**
     * Soft-delete a live workspace. Preserves all visit/settlement history and
     * hides it from the mobile app. Blocked while visitors are still checked in.
     */
    public function destroy(Request $request, Workspace $workspace, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $activeVisits = $workspace->visits()
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->count();

        if ($activeVisits > 0) {
            return back()->withErrors([
                'delete' => "لا يمكن حذف مساحة العمل ويوجد {$activeVisits} زائر مسجّل دخول حاليًا. يجب تسجيل خروجهم أولاً.",
            ]);
        }

        DB::transaction(function () use ($workspace): void {
            $workspace->update(['is_active' => false]);
            $workspace->delete();
        });

        $logger->handle((string) Auth::id(), 'DELETE_WORKSPACE', 'Workspace', $workspace->id, [
            'reason' => $validated['reason'],
        ], $request->ip());

        return redirect()->route('admin.workspaces.index')->with('success', 'تم حذف مساحة العمل (يمكن استعادتها).');
    }

    public function restore(string $workspace, LogAdminAction $logger): RedirectResponse
    {
        $model = Workspace::onlyTrashed()->findOrFail($workspace);
        $model->restore();
        // Comes back switched off; the admin re-opens it deliberately.
        $model->update(['is_active' => false]);

        $logger->handle((string) Auth::id(), 'RESTORE_WORKSPACE', 'Workspace', $model->id, null, request()->ip());

        return redirect()->route('admin.workspaces.show', $model)->with('success', 'تم استعادة مساحة العمل (غير مفعّلة).');
    }
}
