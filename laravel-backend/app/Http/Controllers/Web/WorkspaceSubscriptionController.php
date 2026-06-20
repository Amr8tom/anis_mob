<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspaceSubscription\Actions\AssignWorkspaceSubscriptionByPhoneAction;
use App\Domain\WorkspaceSubscription\Actions\CancelWorkspaceSubscriptionAction;
use App\Domain\WorkspaceSubscription\Actions\CreateWorkspacePlanAction;
use App\Domain\WorkspaceSubscription\Actions\DeactivateWorkspacePlanAction;
use App\Domain\WorkspaceSubscription\Actions\GenerateWorkspaceSubscriptionCodeAction;
use App\Domain\WorkspaceSubscription\Actions\GetWorkspaceSubscriptionDashboardAction;
use App\Domain\WorkspaceSubscription\Actions\RevokeWorkspaceSubscriptionCodeAction;
use App\Domain\WorkspaceSubscription\Actions\UpdateWorkspacePlanAction;
use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Data\WorkspacePlanData;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkspaceSubscriptionController extends Controller
{
    public function index(Request $request, GetWorkspaceSubscriptionDashboardAction $dashboard): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $data = $dashboard->handle($workspace->id);

        return view('workspace.subscriptions.index', array_merge($data, compact('workspace')));
    }

    public function storePlan(Request $request, CreateWorkspacePlanAction $action): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $this->validatePlan($request);

        $action->handle($workspace->id, $this->planData($validated));

        return back()->with('success', 'تم إنشاء الباقة بنجاح.');
    }

    public function updatePlan(Request $request, string $plan, UpdateWorkspacePlanAction $action, WorkspacePlanRepositoryInterface $plans): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $plans->findForWorkspace($plan, $workspace->id);
        abort_if($model === null, 404);

        $validated = $this->validatePlan($request);
        $action->handle($model, $this->planData($validated));

        return back()->with('success', 'تم تحديث الباقة.');
    }

    public function deactivatePlan(string $plan, DeactivateWorkspacePlanAction $action, WorkspacePlanRepositoryInterface $plans): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $plans->findForWorkspace($plan, $workspace->id);
        abort_if($model === null, 404);

        $action->handle($model);

        return back()->with('success', 'تم إيقاف الباقة.');
    }

    public function generateCodes(Request $request, GenerateWorkspaceSubscriptionCodeAction $action, WorkspacePlanRepositoryInterface $plans): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'workspace_plan_id' => ['required', 'uuid'],
            'count' => ['required', 'integer', 'min:1', 'max:200'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $plan = $plans->findForWorkspace($validated['workspace_plan_id'], $workspace->id);
        abort_if($plan === null, 404);

        $expiresAt = isset($validated['expires_in_days'])
            ? now()->addDays((int) $validated['expires_in_days'])
            : null;

        $codes = $action->handle($plan, (string) Auth::id(), (int) $validated['count'], $expiresAt);

        return back()
            ->with('success', 'تم توليد '.count($codes).' كود تفعيل.')
            ->with('generated_workspace_codes', collect($codes)->pluck('code')->all());
    }

    public function assignByPhone(Request $request, AssignWorkspaceSubscriptionByPhoneAction $action, WorkspacePlanRepositoryInterface $plans): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'workspace_plan_id' => ['required', 'uuid'],
            'phone_number' => ['required', 'string', 'max:30'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $plan = $plans->findForWorkspace($validated['workspace_plan_id'], $workspace->id);
        abort_if($plan === null, 404);

        try {
            $action->handle($plan, $validated['phone_number'], (string) Auth::id(), $validated['name'] ?? null);
        } catch (ApiException $e) {
            return back()->withErrors(['phone_number' => $e->getMessage()])->withInput();
        }

        return back()->with('success', 'تم تفعيل الاشتراك للزائر بنجاح.');
    }

    public function cancelSubscription(string $subscription, CancelWorkspaceSubscriptionAction $action): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;

        $action->handle($subscription, $workspace->id);

        return back()->with('success', 'تم إلغاء الاشتراك.');
    }

    public function revokeCode(string $code, RevokeWorkspaceSubscriptionCodeAction $action): RedirectResponse
    {
        $action->handle($code, Auth::user()->ownedWorkspace->id);

        return back()->with('success', 'تم إلغاء كود التفعيل.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePlan(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'price_pounds' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function planData(array $validated): WorkspacePlanData
    {
        return new WorkspacePlanData(
            name: (string) $validated['name'],
            includedMinutes: (int) $validated['hours'] * 60,
            durationDays: (int) $validated['duration_days'],
            priceCents: isset($validated['price_pounds']) ? (int) round(((float) $validated['price_pounds']) * 100) : null,
            currency: 'EGP',
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }
}
