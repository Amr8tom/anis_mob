# Production Scaling Runbook

## Architecture

- Run at least two stateless Laravel application instances behind a load balancer.
- Use managed MySQL with automatic backups, point-in-time recovery, one writer, and one or more read replicas.
- Use Redis Cluster or a managed Redis service for cache, sessions, distributed scheduler locks, rate limiting, unique jobs, and queues.
- Use S3-compatible object storage behind a CDN for workspace media.

## Required Environment

```env
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_WRITE_HOST=mysql-writer.internal
DB_READ_HOST=mysql-reader-1.internal,mysql-reader-2.internal
DB_STICKY=true

CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_CONNECTION=default
QUEUE_CONNECTION=redis
REDIS_URL=rediss://...
REDIS_QUEUE_BLOCK_FOR=5

WORKSPACE_MEDIA_DISK=s3
AWS_URL=https://cdn.example.com
```

## Processes

Run these as separate scalable services:

```bash
php-fpm
php artisan queue:work redis --queue=attendance,reports,default --sleep=1 --tries=5 --timeout=180
php artisan schedule:work
```

Only one scheduler task executes due to `onOneServer()` distributed Redis locks.

If the host uses cron instead of `schedule:work`, install this every minute:

```cron
* * * * * cd /path/to/laravel-backend && php artisan schedule:run >> /dev/null 2>&1
```

## Workspace Data Scaling Operations

Visitor search must use `workspace_clients`; do not rebuild the clients page from grouped raw `workspace_visits`.

Run once after deploying the workspace-client summary migration:

```bash
php artisan workspace-clients:backfill
php artisan reports:backfill-rollups --from=2026-01-01 --to=$(date +%F)
```

Recurring operations:

```bash
php artisan workspace-scaling:health
php artisan reports:rollup-visits
php artisan workspace-visits:retention --dry-run
php artisan workspace-visits:retention --older-than-days=365 --max-delete=10000
```

Useful repair commands:

```bash
php artisan workspace-clients:repair --workspace=<workspace-id>
php artisan reports:backfill-rollups --workspace=<workspace-id> --from=YYYY-MM-DD --to=YYYY-MM-DD
```

Retention rules:

- Raw checked-out visits older than one year are archived to `workspace_visit_archives` before deletion.
- Active visits are never deleted.
- Visits without `workspace_client_counted_at` are skipped.
- Visits without daily rollups are skipped.
- Visits referenced by accounting corrections or refunds are skipped.
- Keep subscription, ledger, settlement, and accounting audit tables forever.

Known reporting limitation:

- Daily rollups store unique visitors per day and source. Summing those rows across long ranges can count the same visitor on multiple days more than once. If exact unique visitors across long multi-day ranges becomes a hard requirement, add a `workspace_daily_visitors` table keyed by workspace, date, source, client type, and visitor id.

## Database Recommendations

- Use MySQL 8 or PostgreSQL for production, not SQLite.
- Enable the slow query log and start with `long_query_time=1`.
- Size `innodb_buffer_pool_size` to the available RAM on dedicated MySQL hosts.
- Use Redis for cache, sessions, scheduler locks, and queues once traffic grows.
- Track queue age, failed jobs, slow queries, retention backlog, and uncounted checked-out visits.

## Deployments

1. Run additive migrations before deploying code that uses new columns.
2. Deploy application instances gradually behind the load balancer.
3. Run `php artisan optimize`.
4. Restart queue workers with `php artisan queue:restart`.
5. Verify `/up` and `/api/health/ready`.

## Monitoring

Alert on:

- API p95 latency above 300 ms.
- Error rate above 0.1%.
- Readiness endpoint failure.
- Failed jobs above the configured threshold.
- Queue age above 60 seconds.
- MySQL CPU above 70%, replica lag, connection saturation, and slow queries.
- Redis memory, evictions, rejected connections, and queue depth.

Request logs include request ID, route, status, duration, and user ID. Slow database queries and failed queue jobs emit structured warning/error logs.

## Load Tests

Install k6 and run:

```bash
k6 run -e BASE_URL=https://staging.example.com load-tests/k6-smoke.js
k6 run -e BASE_URL=https://staging.example.com -e API_TOKEN=... -e QR_TOKEN=... -e RATE=100 load-tests/k6-attendance.js
```

Increase traffic gradually. Do not claim a supported traffic level until the target rate passes repeatedly against production-sized data with the configured thresholds.
