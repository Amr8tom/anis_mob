<?php

namespace App\Http\Controllers\Web;

use App\Domain\Admin\Actions\LogAdminAction;
use App\Domain\Subscription\Actions\GeneratePlanCodeAction;
use App\Domain\Subscription\Actions\RevokePlanCodeAction;
use App\Enums\PlanTier;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanActivationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPlanCodeController extends Controller
{
    public function index()
    {
        $codes = PlanActivationCode::with(['plan', 'usedBy'])->latest()->paginate(20);
        $plans = Plan::query()->where('is_active', true)
            ->where('tier', '!=', PlanTier::FREE->value)
            ->get();

        return view('admin.plancodes.index', compact('codes', 'plans'));
    }

    public function store(Request $request, GeneratePlanCodeAction $generateAction, LogAdminAction $logger)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'count' => 'required|integer|min:1|max:50',
            'expires_in_days' => 'nullable|integer|min:1',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        abort_if($plan->tier === PlanTier::FREE, 422, 'Activation codes cannot be generated for the free plan.');
        $expiresAt = isset($validated['expires_in_days']) ? now()->addDays((int) $validated['expires_in_days']) : null;

        $codes = $generateAction->handle($plan, Auth::id(), $expiresAt, (int) $validated['count']);

        $logger->handle(
            adminId: Auth::id(),
            action: 'GENERATE_PLAN_CODES',
            entityType: 'Plan',
            entityId: $plan->id,
            details: ['count' => $validated['count'], 'generated_ids' => collect($codes)->pluck('id')->toArray()]
        );

        return redirect()->back()->with('success', "{$validated['count']} codes generated successfully.");
    }

    public function revoke(PlanActivationCode $planCode, RevokePlanCodeAction $revokeAction, LogAdminAction $logger)
    {
        $revokeAction->handle($planCode, Auth::id());

        $logger->handle(
            adminId: Auth::id(),
            action: 'REVOKE_PLAN_CODE',
            entityType: 'PlanActivationCode',
            entityId: $planCode->id
        );

        return redirect()->back()->with('success', 'Code voided successfully.');
    }
}
