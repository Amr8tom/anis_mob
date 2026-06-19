<?php

namespace Tests\Feature\Subscription;

use App\Domain\Subscription\Actions\ActivatePlanCodeAction;
use App\Domain\Subscription\Data\ActivatePlanCodeData;
use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\PlanActivationCode;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_have_multiple_active_subscriptions_concurrently(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['tier' => 'SILVER']);

        // Let's create an active subscription directly
        Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
        ]);

        // Attempt to insert another active subscription manually simulating a race condition bypass
        try {
            Subscription::factory()->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => SubscriptionStatus::ACTIVE,
                'active_flag' => 1,
            ]);
            $this->fail('Unique constraint on user_id and active_flag did not prevent duplicate active subscriptions.');
        } catch (QueryException $e) {
            // The exception should be a unique constraint violation (code 23000)
            $this->assertEquals(23000, $e->getCode());
        }

        // Verify only 1 active subscription exists
        $this->assertEquals(1, Subscription::where('user_id', $user->id)->where('active_flag', 1)->count());
    }

    public function test_activate_plan_action_replaces_active_subscription_correctly(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['tier' => 'SILVER', 'included_minutes' => 600]);

        Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
        ]);

        $code = PlanActivationCode::create([
            'plan_id' => $plan->id,
            'code' => 'TESTCODE',
            'is_used' => false,
        ]);

        $action = app(ActivatePlanCodeAction::class);
        $data = new ActivatePlanCodeData(code: 'TESTCODE');

        // Activate a new one
        $newSub = $action->handle($user, $data);

        $this->assertEquals(SubscriptionStatus::ACTIVE, $newSub->status);
        $this->assertEquals(1, $newSub->active_flag);

        // The old one should be EXPIRED
        $this->assertEquals(1, Subscription::where('user_id', $user->id)->where('status', 'EXPIRED')->whereNull('active_flag')->count());
        $this->assertEquals(1, Subscription::where('user_id', $user->id)->where('status', 'ACTIVE')->where('active_flag', 1)->count());
    }
}
