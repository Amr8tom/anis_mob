<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\PlanActivationCode;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateActivationCodeCommand extends Command
{
    protected $signature = 'code:generate {--plan= : The ID of the plan} {--prefix= : Prefix for the code (e.g. VIP)} {--count=1 : Number of codes to generate}';

    protected $description = 'Generate plan activation codes';

    public function handle(): int
    {
        $planId = $this->option('plan');
        $prefix = $this->option('prefix') ? strtoupper((string) $this->option('prefix')).'-' : '';
        $count = (int) $this->option('count');

        if (! $planId) {
            $this->error('Please provide a --plan UUID.');

            return self::FAILURE;
        }

        $plan = Plan::find($planId);
        if (! $plan) {
            $this->error("Plan not found: {$planId}");

            return self::FAILURE;
        }

        $this->info("Generating {$count} code(s) for Plan '{$plan->name}'...");

        for ($i = 0; $i < $count; $i++) {
            $code = $prefix.strtoupper(Str::random(8));
            PlanActivationCode::create([
                'code' => $code,
                'plan_id' => $planId,
            ]);
            $this->line("Code: <fg=green>{$code}</>");
        }

        return self::SUCCESS;
    }
}
