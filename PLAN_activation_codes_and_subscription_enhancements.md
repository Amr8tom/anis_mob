# Agent Prompt — Activation Codes, Subscription Lifecycle, Home/Profile Balance Display, Workspace Images Fix

You are a senior Laravel + Flutter engineer working on the **Anis** app (study-workspace platform).

**MANDATORY: read these files first and follow them exactly for every feature you build:**
- `laravel-backend/feature_template_for_laravel_.md` (backend standard: Domain Actions, Repository interfaces, DTOs, Form Requests, Resources, Policies, enums, transactions + row locks, ledger audit, response envelope `{success,data,message}`, tests, Pint + Larastan level 8)
- `FEATURE_TEMPLATE.md` in the Flutter root (Clean Architecture: domain entities + use cases + repository interface, data models + remote/local sources + repository impl, presentation Cubit + thin widgets, read-with-offline-cache, write-online-first)

Also read before coding: `laravel-backend/backend_design.md`, existing Domain folders (`Domain/Subscription`, `Domain/Attendance`), `routes/api.php`, `app/Enums/*`, migrations in `database/migrations/`, and the Flutter features `lib/features/home`, `lib/features/profile`, `lib/features/workspaces`, `lib/features/subscriptions` (or closest equivalent).

## Existing context (verified)

- Roles: `UserRole` enum = `USER | ADMIN | WORKSPACE_OWNER`. No admin API/portal exists yet.
- `plans`: uuid PK, `tier` enum `FREE|SILVER|GOLD`, `included_minutes` (time-balance plans), `duration_days` (date-bound plans), `price_cents`.
- `subscriptions`: uuid PK, `user_id`, `plan_id`, `status ACTIVE|EXPIRED|CANCELLED`, `started_at`, `expires_at`, `remaining_minutes`, `active_flag` (nullable tinyint; `unique(user_id, active_flag)` enforces one ACTIVE subscription per user — preserve this invariant in every status change: set `active_flag = null` whenever status leaves ACTIVE).
- `subscription_ledgers`: immutable audit rows (`change_minutes`, `balance_after`, `reason`).
- Check-in requires a usable non-FREE subscription when workspace `hour_multiplier > 0`; check-out deducts minutes inside a transaction with `lockForUpdate`.
- Workspaces have `cover_image_url` + `gallery_images` (json array of URLs).

---

## Feature 1 — Admin-generated plan activation codes

Goal: an ADMIN generates one-time codes bound to a plan (Silver/Gold). The admin sends the code to a member out-of-band (WhatsApp). The member enters the code in the app → their plan activates immediately.

### Database

New table `activation_codes`:

- `id` uuid PK
- `code` string, unique — generated server-side: 12 chars, unambiguous alphabet (no 0/O/1/I), format `XXXX-XXXX-XXXX`. Store only a SHA-256 hash if you want defense in depth; otherwise store plaintext but treat as secret (acceptable for v1 — choose plaintext + unique index for simplicity, document the decision).
- `plan_id` FK → plans (the plan this code activates)
- `created_by` FK → users (the admin)
- `status` enum `UNUSED | REDEEMED | REVOKED | EXPIRED` (string column + PHP enum, NOT a MySQL enum — easier to alter)
- `redeemed_by` nullable FK → users
- `redeemed_at` nullable timestamp
- `subscription_id` nullable FK → subscriptions (the subscription it created — audit link)
- `expires_at` nullable timestamp (code validity window, e.g. admin sets 30 days)
- `batch_id` nullable uuid + `note` nullable string (admins generate codes in batches with a label)
- timestamps
- Indexes: `unique(code)`, `index(status)`, `index(batch_id)`

### Backend (follow the Laravel template fully: Domain/ActivationCode/*)

- `GenerateActivationCodesAction` — admin creates N codes (1–500) for a plan, optional expiry + note. Returns the batch. Code generation must retry on the (astronomically unlikely) unique collision.
- `RedeemActivationCodeAction` — the critical money-path action. Inside `DB::transaction`:
  1. `lockForUpdate` the code row by code string; reject if not found / not UNUSED / past `expires_at` → domain exceptions mapped to 404/409/410.
  2. If the user already has an ACTIVE non-FREE subscription → reject 409 (`AlreadyHasActiveSubscriptionException`) — v1 does not stack or extend.
  3. Expire/cancel any ACTIVE FREE subscription (set status EXPIRED, `active_flag = null`).
  4. Create the subscription: `started_at = now()`, `expires_at = now()->addDays(30)` **always** (see Feature 2 — hard 30-day cap even for minute-balance plans; if plan has `duration_days`, use `min(duration_days, 30)`... no: use `duration_days ?? 30` but never null), `remaining_minutes = plan.included_minutes`, `active_flag = 1`.
  5. Mark code REDEEMED with `redeemed_by`, `redeemed_at`, `subscription_id`.
  6. Write a `subscription_ledgers` row: `change_minutes = +included_minutes` (or 0 for date-bound), `balance_after`, `reason = 'CODE_ACTIVATION'`.
  7. Handle unique-violation race on `unique(user_id, active_flag)` → clean 409.
- `RevokeActivationCodeAction` — admin revokes UNUSED codes.
- Policies: `ActivationCodePolicy` — generate/list/revoke require `role === ADMIN` (add a `role:admin`-style check via policy, not middleware string magic). Redeem requires any authenticated non-guest user.
- Routes:
  - `POST /api/v1/admin/activation-codes` (generate batch)
  - `GET /api/v1/admin/activation-codes` (paginated, filter by status/batch/plan)
  - `POST /api/v1/admin/activation-codes/{id}/revoke`
  - `POST /api/v1/activation-codes/redeem` `{code}` — throttle hard: `throttle:redeem` = 5/min per user (brute-force guard), and rate-limit failures.
- Form Requests, DTOs, Resources (never expose other users' data; code list shows redeemer name only to admins), feature tests per the template checklist (happy + 401/403/404/409/410/422 + concurrency/double-redeem test + cross-user denial).
- Seeder: an admin user + a demo batch.

### Flutter

New feature `lib/features/activation_code` per FEATURE_TEMPLATE.md:
- Entity `ActivationResultEntity` (new subscription summary), use case `RedeemActivationCode`, repository + remote source (`POST /activation-codes/redeem`), Cubit with `initial/submitting/success/error` states.
- UI: "Activate plan with code" entry point on the Plans/Subscription screen and in Profile. A single screen: code input (auto-uppercase, auto-format `XXXX-XXXX-XXXX`), submit button, success state showing the activated plan (tier, hours, expiry date), localized errors for invalid/used/expired code. Arabic + English strings.
- On success: refresh home profile + subscription state (invalidate caches).

Note: no admin UI in Flutter for v1. Admin generation happens via API (Postman) or — if time allows — a minimal Blade page in the existing web portal area guarded by an `EnsureAdmin` middleware, styled like the workspace portal.

---

## Feature 2 — Subscription lifecycle: hard 30-day expiry + auto-downgrade to Free

Rules:
1. Every paid subscription gets `expires_at = started_at + 30 days` (or `plan.duration_days` if set and smaller). Minute-balance plans are no longer open-ended: **after 30 days the subscription expires even if `remaining_minutes > 0`**.
2. On expiry: status → EXPIRED, `active_flag = null`, forfeit remaining minutes with a ledger row (`change_minutes = -remaining`, `balance_after = 0`, `reason = 'EXPIRY_FORFEIT'`), then create/ensure an ACTIVE FREE subscription for the user (seed a canonical FREE plan; idempotent "ensure free plan" helper).

Implementation:
- Backfill migration: set `expires_at = started_at + 30 days` for existing ACTIVE subscriptions where `expires_at` is null.
- `ExpireSubscriptionsCommand` (`subscriptions:expire`) scheduled every 5 minutes (mirror `AutoCheckOutCommand` style): select ACTIVE subscriptions with `expires_at <= now()`, process each in its own transaction with `lockForUpdate`, apply rule 2. Index `(status, expires_at)`.
- Defense in depth: `CheckInAction` already rejects past-`expires_at` subscriptions — keep that, and ALSO make `activeForUser`/`lockActiveForUser` treat past-expiry as unusable so a lagging cron can never let a stale plan check in. Fix the existing checkout gap: `deduct()` must target the subscription captured on the visit (`visit.subscription_id`), not "whatever is active now".
- Unit tests: expiry forfeits minutes + writes ledger + creates free sub; expiry is idempotent; a user mid-visit at expiry time still checks out correctly.

---

## Feature 3 — Balance display: hours/days/sessions left on Home and Profile

Backend:
- Extend the subscription summary (used by `GET /home/profile`, `GET /profile`, `GET /subscriptions/current` — there is an existing `App\Support\SubscriptionSummary`) to return for the active subscription:
  - `plan_name`, `tier`
  - `remaining_minutes` and derived `remaining_hours` (decimal, 1dp) — null for date-bound plans
  - `total_minutes` (plan.included_minutes) for progress bars
  - `days_left` = whole days until `expires_at` (ceil, min 0), `expires_at` ISO
  - `sessions_count` = the user's `total_sessions` (already on users) and `visits_this_period` = count of visits since `started_at`
  - `is_free_tier` boolean
- Keep it one query, eager-loaded, no N+1. Update Resources + tests.

Flutter:
- Home screen: a subscription card/banner under the profile header showing tier badge (Silver/Gold colors), hours left (e.g. "١٢٫٥ ساعة متبقية"), days left ("ينتهي خلال ٩ أيام"), linear progress bar (remaining/total), and a CTA → activation-code screen when on Free. Skeletonizer placeholder while loading; cached value shown offline.
- Profile screen: same data in the stats row — hours left, days left, sessions count (replacing/extending the current static counters).
- Update entity/model/use case/cubit per the template; do not parse JSON in widgets.

---

## Feature 4 — Workspace image pipeline (upload, variants, CDN delivery)

Problems reported: gallery/cover images render poorly on the web portal and break in the mobile app. Replace the raw-URL-string approach with a proper image pipeline.

### Architecture decisions (fixed — do not deviate)

- **Storage: S3-compatible object storage via Laravel's `Storage` facade** (`league/flysystem-aws-s3-v3`). Target **Cloudflare R2** (zero egress) — but write all code against the generic `s3` disk config so any S3-compatible provider works. Add a `media` disk in `config/filesystems.php` driven entirely by env vars (`MEDIA_DISK`, endpoint, bucket, keys, `MEDIA_PUBLIC_BASE_URL`). **Local fallback:** when `MEDIA_DISK=public`, everything must still work on the local `public` disk + `storage:link` for dev/test — tests run against the local disk with `Storage::fake()`.
- **Upload flow: through the Laravel API** (no presigned uploads). Validate server-side: `image`, `mimes:jpg,jpeg,png,webp`, `max:5120`, dimension sanity check (min 400px wide), and re-encode every upload (never store the original bytes as-is — re-encoding strips EXIF/GPS metadata and neutralizes malformed-file attacks).
- **Variants:** generate 3 per image with Intervention Image in a **queued job** (`ProcessWorkspaceImageJob`):
  - `thumb` — 400px wide, WebP q80 (lists/cards)
  - `medium` — 1200px wide, WebP q80 (details/gallery)
  - `full` — capped 2000px wide, WebP q82
- **Naming/caching:** content-hashed filenames (`workspaces/{workspaceId}/{sha1}_{variant}.webp`) so URLs are immutable; serve with `Cache-Control: public, max-age=31536000, immutable`. Cache invalidation = new file, new URL. Delete old objects when an image is replaced/removed.
- **Delivery:** absolute URLs built from `MEDIA_PUBLIC_BASE_URL` (the CDN/R2 public domain). Never return relative paths.

### Database

New table `workspace_images` (replaces the json `gallery_images` column as source of truth):

- `id` uuid PK, `workspace_id` FK cascade, `kind` string enum `COVER|GALLERY`, `path_thumb`, `path_medium`, `path_full`, `width`, `height`, `bytes`, `position` int (gallery ordering), `status` string enum `PROCESSING|READY|FAILED`, timestamps. Index `(workspace_id, kind, position)`.
- Migration backfills existing `cover_image_url`/`gallery_images` URLs into rows (kind set, paths = legacy URL in all three variant columns, status READY). Keep the old columns for one release, mark deprecated; `WorkspaceResource` reads only from `workspace_images`.

### Backend (template-compliant: Domain/WorkspaceMedia/*)

- `UploadWorkspaceImageAction`: validate → store original temp → create `workspace_images` row (PROCESSING) → dispatch `ProcessWorkspaceImageJob` → return the row. The job re-encodes variants, uploads to the `media` disk, updates paths + dimensions, sets READY (or FAILED with a log line). Workspace owner portal uploads and admin uploads share this action.
- `DeleteWorkspaceImageAction` + `ReorderGalleryAction` (position updates in one transaction).
- Policy: only the workspace's owner (`owner_id`) or ADMIN may upload/delete/reorder. Cross-owner denial tests.
- Routes (web portal controllers may call the same actions): `POST /api/v1/admin/workspaces/{id}/images`, `DELETE .../images/{imageId}`, `PATCH .../images/reorder` — plus portal Blade endpoints.
- `WorkspaceResource` / `WorkspaceVisitResource` etc. return per image: `{id, kind, thumb_url, medium_url, full_url, position}` — absolute URLs, `[]` when none, only READY images exposed publicly.
- Portal Blade settings page: file inputs with client-side preview, per-image delete + drag-reorder, fixed aspect-ratio containers with `object-fit: cover`. Show "processing" state for not-yet-READY images.
- Seeders: working placeholder images committed under `database/seed-assets/` and copied through the pipeline (or stable placeholder URLs).

### Flutter

- Model layer: parse the `{thumb_url, medium_url, full_url}` shape; treat null/empty/invalid as "no image"; if a URL is relative (legacy rows), prefix the API base URL.
- **Variant per context:** `thumb_url` in lists/cards, `medium_url` in details/gallery, `full_url` only in a full-screen viewer. Never load `full` in a scrolling list.
- `cached_network_image` everywhere (already a dependency): skeletonizer shimmer placeholder, branded `errorWidget` fallback asset (never a broken-image icon or crash), explicit `fit: BoxFit.cover`, `memCacheWidth` matched to the rendered size.
- Fix `workspace_gallery_image.dart` and replace any `Image.network` usages.

### Tests

- Unit: variant generation (dimensions, WebP output, EXIF stripped), content-hash naming, replace-deletes-old-objects.
- Feature: upload happy path (queued job asserted), 403 cross-owner, 422 bad mime/too large, resource returns absolute URLs and `[]` empty state, only READY images visible publicly. All against `Storage::fake('media')`.

---

## Delivery order & quality gates

1. Feature 2 (lifecycle) → 2. Feature 1 (codes) → 3. Feature 3 (display) → 4. Feature 4 (images).
2. Backend gates per template §5/§7: Pint clean, Larastan level 8 clean, `php artisan test` green, migrations reversible (`migrate:fresh` + rollback), factories + seeders, no N+1, every new route policy-gated + throttled, feature tests cover 401/402/403/404/409/410/422 + concurrency races, all balance changes write ledger rows in-transaction.
3. Flutter gates: `flutter analyze` clean, domain never imports data/UI, all strings localized (ar/en), states for loading/empty/error/offline, `flutter test` green for new cubits/use cases.
4. Do not break existing API contracts; only add fields. Run the full existing test suite at the end.
