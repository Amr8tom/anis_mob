<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class HealthController extends Controller
{
    public function ready(): JsonResponse
    {
        $checks = [];

        try {
            DB::select('select 1');
            $checks['database'] = 'ok';
        } catch (\Throwable $exception) {
            $checks['database'] = $exception->getMessage();
        }

        try {
            Cache::put('health:ready', 'ok', 10);
            $checks['cache'] = Cache::get('health:ready') === 'ok' ? 'ok' : 'failed';
        } catch (\Throwable $exception) {
            $checks['cache'] = $exception->getMessage();
        }

        $failedJobs = DB::table('failed_jobs')->count();
        $checks['failed_jobs'] = $failedJobs;
        $healthy = $checks['database'] === 'ok'
            && $checks['cache'] === 'ok'
            && $failedJobs <= (int) env('HEALTH_MAX_FAILED_JOBS', 10);

        return response()->json(['status' => $healthy ? 'ready' : 'not_ready', 'checks' => $checks], $healthy ? 200 : 503);
    }
}
