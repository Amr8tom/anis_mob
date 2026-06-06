# anis Backend — Step-by-Step After Migrations

Do these in order. Each step has the exact command(s) to run from inside `laravel-backend/`.
Follow `feature_template_for_laravel_.md` for every feature you build.

---

## Step 0 — One-time setup (do once)

```bash
cd laravel-backend

# 1. Install API + Sanctum (adds routes/api.php, personal_access_tokens migration, bootstrap wiring)
composer require laravel/sanctum
php artisan install:api

# 2. Quality tools the template requires
composer require --dev laravel/pint larastan/larastan

# 3. Run the migrations you already have
php artisan migrate
```

If `php artisan migrate` succeeds with no errors, your database is built. ✅
If you prefer MySQL over SQLite: edit `.env` (`DB_CONNECTION=mysql`, db name/user/pass), create the DB, then `php artisan migrate:fresh`.

---

## Step 1 — Make the User model auth-ready

Open `app/Models/User.php` and:

1. Add `use Laravel\Sanctum\HasApiTokens;` and `use Illuminate\Database\Eloquent\Concerns\HasUuids;`
2. Add the traits: `use HasApiTokens, HasFactory, Notifiable, HasUuids;`
3. Set `protected $keyType = 'string';` and `public $incrementing = false;` (because the PK is a UUID).
4. Set `$fillable` to: `full_name, phone_number, whatsapp_number, password, role, is_guest, university, initials, study_field, avatar_url`.
5. Set `$hidden` to: `password, remember_token`.
6. In `casts()` add: `'password' => 'hashed'`, `'interests' => 'array'`, `'is_guest' => 'boolean'`, `'rating' => 'decimal:2'`, `'wallet_balance' => 'decimal:2'`.

> Tip: Laravel 13 lets you use attributes (`#[Fillable([...])]`) instead of the `$fillable` property — either style is fine, just be consistent.

---

## Step 2 — Build the rest of the Eloquent models

Create one model per table. Run:

```bash
php artisan make:model Plan
php artisan make:model Subscription
php artisan make:model Workspace
php artisan make:model WorkspaceDrink
php artisan make:model StudySession
php artisan make:model SessionParticipant
php artisan make:model WorkspaceVisit
php artisan make:model Badge
php artisan make:model SubscriptionLedger
```

For **each** model (per the template):
- Add `use HasUuids;`, `protected $keyType = 'string';`, `public $incrementing = false;`.
- Add `protected $guarded = ['id'];` (or an explicit `$fillable`).
- Add `casts()` for dates / json / enums (e.g. `start_time => datetime`, `rules => array`, `amenities => array`, `gallery_images => array`).
- Add the relationships (see the map below).
- Remember `StudySession` needs `protected $table = 'study_sessions';`.

**Relationships to wire:**
- `User`: hasMany `subscriptions`, `hostedSessions` (StudySession, `host_id`), `visits`, `badges` (belongsToMany via `user_badges`); belongsToMany `joinedSessions` (StudySession via `session_participants`).
- `Plan`: hasMany `subscriptions`.
- `Subscription`: belongsTo `user`, `plan`; hasMany `ledgers`.
- `Workspace`: hasMany `sessions` (StudySession), `visits`, `drinks`.
- `StudySession`: belongsTo `workspace`, `host` (User, `host_id`); belongsToMany `participants` (User via `session_participants`).
- `WorkspaceVisit`: belongsTo `user`, `workspace`, `subscription`.
- `SubscriptionLedger`: belongsTo `subscription`, `user`.

---

## Step 3 — Enums (no magic strings)

```bash
php artisan make:enum Enums/UserRole
php artisan make:enum Enums/SessionStatus
php artisan make:enum Enums/SessionType
php artisan make:enum Enums/VisitStatus
php artisan make:enum Enums/WorkspaceStatus
php artisan make:enum Enums/SubscriptionStatus
php artisan make:enum Enums/PlanTier
php artisan make:enum Enums/Availability
```

Make each a backed string enum with the exact values from the migrations (e.g. `SessionStatus: UPCOMING, IN_PROGRESS, ENDED, CANCELLED`). Then cast them in the models (`'status' => SessionStatus::class`).

---

## Step 4 — Factories + seeders (so you have demo data)

```bash
php artisan make:factory PlanFactory
php artisan make:factory WorkspaceFactory
php artisan make:factory StudySessionFactory
# ...one per model you need in tests/demo

php artisan make:seeder DemoSeeder
```

Fill `DemoSeeder` with: a few Plans (free/silver/gold), 3–4 Workspaces (with drinks/amenities/gallery), one demo User with an ACTIVE subscription, and a couple of sessions. Register it in `database/seeders/DatabaseSeeder.php`, then:

```bash
php artisan db:seed
```

---

## Step 5 — Shared plumbing (build once, used everywhere)

1. `app/Support/ApiResponse.php` — the `{ success, data, message }` + `meta` helper (copy from feature template §3).
2. `app/Exceptions/Handler.php` — map domain exceptions to HTTP codes (402/403/404/409) into that envelope.
3. In a service provider's `boot()`: `Model::preventLazyLoading(! app()->isProduction());` (catches N+1 in dev).

---

## Step 6 — Build features one slice at a time (in this order)

For **each** feature, follow the template's section 7 checklist: Migration ✅ (done) → Model → Enum → Repository interface + Eloquent impl (bind in a provider) → DTO → Action → Form Request → Policy → Resource → thin Controller → routes under `/api/v1` (`auth:sanctum`) → feature tests (happy + 401/402/403/404/409/422) → Pint + Larastan + tests green.

Recommended build order (matches what the Flutter app calls):

1. **Auth** — `POST /auth/register`, `POST /auth/login`, guest login, `GET /auth/me`, logout. Match the app payloads: register `{ full_name, phone_number, whatsapp_number, password }`; login `{ phone_number, password }`; responses return `data.token` and `data.user` (with `role`).
2. **Workspaces** — `GET /workspaces` (paginated, with distance), `GET /workspaces/{id}` (drinks, amenities, gallery, sessions).
3. **Sessions (= buddy)** — list, detail (with participants + founder), create, join, leave. One controller, two Resources (home card vs buddy detail).
4. **Attendance (QR)** — `POST /visits/check-in`, `POST /visits/{id}/check-out`, `GET /visits/open`. Row-locked transaction + ledger write (template §6).
5. **Subscription / Plans** — `GET /plans`, `GET /subscriptions/active` (convert minutes → days with `day_calculation_hours`).
6. **Profile** — `GET` profile, edit profile, reset password, badges/gamification.

---

## Step 7 — Per-feature verification (run before you call it done)

```bash
vendor/bin/pint                 # code style — zero diffs
vendor/bin/phpstan analyse      # Larastan level 8 — no errors
php artisan test                # all green, incl. every error path
```

Then point the Flutter app at your server: edit `lib/core/constants/api_constants.dart` → `baseUrl` to your machine's API URL, and update the older camelCase Flutter models to read snake_case keys (the new auth/attendance models already do).

---

### Quick reference — what's already done
- ✅ All migrations (`database/migrations/2026_06_06_*`) + the rewritten users table.
- ✅ Schema reconciled with every Flutter entity (`backend_design.md`).
- ✅ Coding standard documented (`feature_template_for_laravel_.md`).

Everything from Step 0 onward is yours to run by hand.
