<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class WorkspaceDailyVisitStat extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'stat_date' => 'date',
            'billing_source' => \App\Enums\BillingSource::class,
            'visits_count' => 'integer',
            'visitors_count' => 'integer',
            'unique_visitors_count' => 'integer',
            'registered_visitors_count' => 'integer',
            'walk_in_visitors_count' => 'integer',
            'total_minutes' => 'integer',
        ];
    }
}
