# Workspace-Scoped Subscriptions — Step-by-Step Build Guide (for an AI coding agent)

## HOW TO USE THIS DOCUMENT (read first, follow literally)

You are implementing one feature in the Anis project (Laravel backend + Flutter app).
Work **one numbered step at a time, in order**. Do NOT skip ahead.

Rules you must obey on every step:
1. Before writing code in a step, OPEN and READ the files listed under "Read first" in that step.
2. After each step, RUN the command in "✅ Verify". It MUST pass before you start the next step. If it fails, fix it before continuing.
3. Match the existing code style. Copy the closest existing file and adapt it. Never invent new patterns.
4. Every PHP file starts with `declare(strict_types=1);`.
5. Do NOT change or delete code unrelated to this feature. The repo has other in-progress work — leave it alone.
6. Do NOT break existing tests. Run the full suite at the end of each phase.
7. If something is unclear, choose the option that matches the existing code, and add a short `// NOTE:` comment. Do not redesign.

Project standards (read these two files once before you start):
- `laravel-backend/feature_template_for_laravel_.md`
- `FEATURE_TEMPLATE.md`

Backend commands (run inside `laravel-backend/`):
- Migrate: `php artisan migrate`
- Tests: `php artisan test`
- Style: `vendor/bin/pint`
- Static analysis: `vendor/bin/phpstan analyse`

Flutter commands (run in repo root):
- Analyze: `flutter analyze`

---

## CONFIRMED RULES (these decisions are final — do not change them)

- R1. Workspace subscriptions are SEPARATE from global subscriptions (own tables).
- R2. A user may have ONE global subscription AND one active subscription PER workspace.
- R3. At its workspace, a workspace subscription takes priority automatically.
- R4. Workspace subscriptions always deduct at multiplier **1.0**.
- R5. Global subscriptions keep using the workspace `hour_multiplier`.
- R6. The daily cap counts REAL consumed attendance minutes, regardless of funding source.
- R7. Free workspaces (multiplier 0) consume NO balance.
- R8. Check-in requires at least **15 usable minutes**.
- R9. Cannot activate a second workspace subscription while one usable active one exists at that workspace.
- R10. Direct phone assignment only if the phone belongs to a registered user; otherwise generate a code.
- R11. Generating codes does NOT create `workspace_subscriptions` rows.
- R12. Never expire a subscription while it funds an active visit — expire right AFTER checkout.
- R13. Workspace-plan visits must be separated from global-plan visits in reports.
- R14. Same single "Scan QR" button in the app. No new scanner. No source-picker.

---

# PHASE 1 — DATABASE (start here)

### Step 1.1 — Create the enums
Read first: `laravel-backend/app/Enums/PlanTier.php` (copy its style).
Create these files in `laravel-backend/app/Enums/`:
- `WorkspaceSubscriptionStatus.php` → `ACTIVE, EXHAUSTED, EXPIRED, CANCELLED`
- `WorkspaceSubscriptionCodeStatus.php` → `UNUSED, REDEEMED, REVOKED, EXPIRED`
- `WorkspaceSubscriptionDelivery.php` → `ACTIVATION_CODE, DIRECT_ASSIGNMENT`
- `BillingSource.php` → `FREE, GLOBAL_SUBSCRIPTION, WORKSPACE_SUBSCRIPTION`
- `WorkspaceLedgerReason.php` → `ACTIVATION, DIRECT_ASSIGNMENT, WORKSPACE_VISIT, EXPIRY_FORFEIT, CANCELLATION, ADMIN_ADJUSTMENT`

Each is a backed string enum, value === case name (e.g. `case ACTIVE = 'ACTIVE';`).

✅ Verify: `vendor/bin/pint app/Enums && php -l app/Enums/BillingSource.php` (no syntax errors).

### Step 1.2 — Create the 4 new tables
Read first: `laravel-backend/database/migrations/2026_06_06_000002_create_subscriptions_table.php` (copy the UUID + active_flag pattern).
Create 4 migration files dated AFTER the latest existing migration (use prefix `2026_06_16_000001` … `000004`).

Table `workspace_plans`:
```
id uuid pk
workspace_id  -> foreignUuid, constrained, restrictOnDelete
name string
included_minutes  unsignedInteger
duration_days     unsignedInteger
price_cents       unsignedInteger nullable
currency          char(3) default 'EGP'
is_active         boolean default true
timestamps
index (workspace_id, is_active)
```

Table `workspace_subscriptions`:
```
id uuid pk
workspace_id        -> foreignUuid, constrained, restrictOnDelete
workspace_plan_id   -> foreignUuid nullable, nullOnDelete
user_id             -> foreignUuid, constrained users, restrictOnDelete
status              string default 'ACTIVE'
active_flag         unsignedTinyInteger nullable
started_at          timestamp nullable
expires_at          timestamp nullable
remaining_minutes   unsignedInteger default 0
total_minutes       unsignedInteger default 0
plan_name_snapshot  string
duration_days_snapshot unsignedInteger
price_cents_snapshot   unsignedInteger nullable
currency            char(3) default 'EGP'
delivery_method     string
issued_by_owner_id  -> foreignUuid nullable (users)
timestamps
unique (user_id, workspace_id, active_flag)
index  (workspace_id, status, expires_at)
index  (user_id, workspace_id, status)
```

Table `workspace_subscription_codes`:
```
id uuid pk
workspace_id        -> foreignUuid constrained
workspace_plan_id   -> foreignUuid constrained
code                string unique
status              string default 'UNUSED'
created_by_owner_id -> foreignUuid (users)
redeemed_by_user_id -> foreignUuid nullable (users)
workspace_subscription_id -> foreignUuid nullable
expires_at   timestamp nullable
redeemed_at  timestamp nullable
revoked_at   timestamp nullable
timestamps
index (workspace_id, status)
index (workspace_plan_id, status)
```

Table `workspace_subscription_ledgers` (append-only, no updated_at):
```
id uuid pk
workspace_subscription_id -> foreignUuid nullable, nullOnDelete
workspace_visit_id        -> foreignUuid nullable, nullOnDelete
workspace_id  -> foreignUuid constrained
user_id       -> foreignUuid constrained
change_minutes integer
balance_after  integer
reason         string
timestamp created_at useCurrent   (NO updated_at)
index (workspace_subscription_id, created_at)
```

✅ Verify: `php artisan migrate` then `php artisan migrate:rollback` then `php artisan migrate` all succeed.

### Step 1.3 — Alter `workspace_visits`
Create migration `2026_06_16_000005_add_billing_source_to_workspace_visits_table.php`:
- add `workspace_subscription_id` foreignUuid nullable nullOnDelete (after `subscription_id`)
- add `billing_source` string default `'FREE'` (after `workspace_subscription_id`)
- add index `(workspace_subscription_id, status)`
- In the SAME migration `up()`, backfill existing rows with raw SQL:
  - set `billing_source = 'GLOBAL_SUBSCRIPTION'` where `subscription_id IS NOT NULL`
  - leave the rest as `'FREE'`

✅ Verify: `php artisan migrate` succeeds; in `php artisan tinker` confirm the columns exist on a `WorkspaceVisit`.

---

# PHASE 2 — MODELS + DOMAIN SKELETON

### Step 2.1 — Models
Read first: `laravel-backend/app/Models/Subscription.php`, `app/Models/WorkspaceVisit.php`.
Create models in `app/Models/`: `WorkspacePlan`, `WorkspaceSubscription`, `WorkspaceSubscriptionCode`, `WorkspaceSubscriptionLedger`.
- UUID primary keys (`HasUuids`), `$keyType='string'`, `$incrementing=false`.
- Cast every status/enum/delivery/reason column to its enum, timestamps to `datetime`.
- The ledger model is append-only: block updates/deletes the same way `WorkspaceVisit` blocks deletes (throw `LogicException`).
- Add relationships: plan→workspace; subscription→workspace,plan,user; code→workspace,plan; ledger→subscription,visit.
On `app/Models/Workspace.php` add `hasMany`: `workspacePlans()`, `workspaceSubscriptions()`.
On `app/Models/User.php` add `hasMany`: `workspaceSubscriptions()`.
Add factories for each model under `database/factories/`.

✅ Verify: `php artisan test --filter=Nothing` (boots the app, catches class errors) OR `php artisan tinker` and `WorkspacePlan::factory()->make();`.

### Step 2.2 — Domain folders + repository interfaces
Read first: `laravel-backend/app/Domain/Subscription/` (mirror its structure).
Create `app/Domain/WorkspaceSubscription/` with subfolders `Actions/ Contracts/ Data/ Repositories/`.
Create repository INTERFACES in `Contracts/` and Eloquent IMPLEMENTATIONS in `Repositories/` for: plans, subscriptions, codes, ledgers. Bind each interface→impl in `app/Providers/AppServiceProvider.php` (copy how existing repos are bound).
Repository methods you will need (add as you go): `createPlan, updatePlan, deactivatePlan, activePlansForWorkspace`; `activeForUserWorkspace(userId, workspaceId)`, `lockActive(id)`, `create`, `expiringWithinDays(workspaceId, days)`; `createCode, lockCodeByValue, markRedeemed, revoke`; `appendLedger`.

✅ Verify: `vendor/bin/phpstan analyse app/Domain/WorkspaceSubscription` has no "unknown class" errors.

---

# PHASE 3 — ACTIONS (business logic)

Read first: `app/Domain/Subscription/Actions/GeneratePlanCodeAction.php`, `ActivatePlanCodeAction.php`, `app/Domain/Attendance/Actions/CheckOutAction.php`.

Create these Actions in `app/Domain/WorkspaceSubscription/Actions/`. Each takes a typed DTO (in `Data/`) and, when it touches balance, runs in `DB::transaction` with `lockForUpdate` and writes a ledger row in the SAME transaction.

- `CreateWorkspacePlanAction`, `UpdateWorkspacePlanAction`, `DeactivateWorkspacePlanAction` — simple CRUD on `workspace_plans`. Deactivate sets `is_active=false` (never hard-delete if referenced).
- `GenerateWorkspaceSubscriptionCodeAction` — make N codes, prefix `WSP-`, format `WSP-XXXX-XXXX` (unambiguous chars). Retry on unique collision. Do NOT create a subscription row (R11).
- `RedeemWorkspaceSubscriptionCodeAction` — transaction: lock code row; reject if not UNUSED / expired (R-codes); reject if user already has a usable active sub at that workspace (R9); create `workspace_subscriptions` from the plan SNAPSHOT (copy name/minutes/days/price into the *_snapshot columns), `status=ACTIVE`, `active_flag=1`, `started_at=now`, `expires_at=now+duration_days`, `remaining_minutes=total_minutes=included_minutes`; mark code REDEEMED (set redeemed_by, redeemed_at, workspace_subscription_id); write ledger reason `ACTIVATION` (`change_minutes=+included_minutes`).
- `AssignWorkspaceSubscriptionByPhoneAction` — like redeem but no code. R10: look up a registered user by phone; if none, FAIL with a clear message telling the owner to generate a code instead. Ledger reason `DIRECT_ASSIGNMENT`.
- `DeductWorkspaceSubscriptionAction` — given a locked subscription + minutes, subtract (clamp at 0), write ledger reason `WORKSPACE_VISIT`; if it hits 0 set status `EXHAUSTED` + clear `active_flag`. (Called from checkout.)
- `ExpireWorkspaceSubscriptionAction` — set status `EXPIRED`, clear `active_flag`, forfeit remaining minutes with ledger reason `EXPIRY_FORFEIT` (`change_minutes = -remaining`, `balance_after=0`). Idempotent.
- `ExpireWorkspaceSubscriptionsAction` — find ACTIVE subs with `expires_at <= now()` that are NOT funding an open visit (R12), expire each via the single-expire action in its own transaction.
- `CancelWorkspaceSubscriptionAction` — owner cancels: status `CANCELLED`, clear `active_flag`, ledger reason `CANCELLATION`.
- `GetWorkspaceSubscriptionDashboardAction` — returns the data the owner tab needs: active count, expiring-in-3-days list, issued list (paginated), plan templates. Eager-load to avoid N+1.

✅ Verify: `vendor/bin/pint app/Domain/WorkspaceSubscription && vendor/bin/phpstan analyse app/Domain/WorkspaceSubscription` clean.

---

# PHASE 4 — ACTIVATION ENDPOINT (reuse the existing one)

Read first: `app/Http/Controllers/Api/V1/SubscriptionController.php`, route `POST /api/v1/subscriptions/activate` in `routes/api.php`.

In the existing activate flow:
1. Normalize the submitted code (uppercase, trim).
2. If it starts with `WSP-` → call `RedeemWorkspaceSubscriptionCodeAction`.
3. Otherwise → keep the existing global activation EXACTLY as-is.
Keep the route throttled + idempotent (it already is). Lock the code row before validating. Reject used/revoked/expired (404/409/410). Reject if user already has a usable active sub at that workspace (409).

Return a response that says which type it is:
```
{ subscription_type: "workspace", workspace_id, workspace_name, plan_name,
  remaining_minutes, total_minutes, expires_at, message }
```
For global activation keep the existing response shape but add `subscription_type: "global"`.

✅ Verify: write/READ a quick feature test hitting `/api/v1/subscriptions/activate` with a `WSP-` code → 200 and a `workspace_subscriptions` row exists. `php artisan test --filter=Activate`.

---

# PHASE 5 — CHECK-IN FUNDING RESOLVER

Read first: `app/Domain/Attendance/Actions/CheckInAction.php`, `app/Domain/Attendance/Actions/OwnerRegisterVisitAction.php`, `app/Domain/Attendance/Repositories/EloquentAttendanceRepository.php` (the `createCheckIn` method).

Create one reusable resolver: `app/Domain/Attendance/Actions/ResolveVisitFundingAction.php` returning a small typed result `{ billingSource, workspaceSubscriptionId|null, subscriptionId|null }`.

Resolution order (R3, R7, R8):
1. If `workspace.hour_multiplier == 0.0` → `FREE`.
2. Else find ACTIVE, non-expired `workspace_subscription` for `(user, workspace)` with `remaining_minutes >= 15` → `WORKSPACE_SUBSCRIPTION`.
3. Else existing usable global subscription (the current logic, incl. the 15-min / expiry checks) → `GLOBAL_SUBSCRIPTION`.
4. Else existing free/blocked behavior (throw the existing `NoActiveSubscriptionException` when a paid workspace has no funding).

Wire BOTH `CheckInAction` and `OwnerRegisterVisitAction` to use this resolver. When creating the visit set exactly one funding FK plus `billing_source` (extend `createCheckIn` to accept these). Never set both FKs.

Show the chosen source + balance in `WorkspaceVisitResource` (add `billing_source`, and when workspace-funded, `remaining_minutes`/`expires_at` of that sub).

✅ Verify: feature test — user with a workspace sub checks in at that workspace → visit has `billing_source=WORKSPACE_SUBSCRIPTION` and `workspace_subscription_id` set, `subscription_id` null. Existing check-in tests still green. `php artisan test --filter=Attendance`.

---

# PHASE 6 — CHECKOUT + DAILY CAP

Read first: `app/Domain/Attendance/Actions/CheckOutAction.php` (study the current cap math), `OwnerCheckOutVisitAction.php`, `app/Jobs/AutoCheckOutVisitJob.php`.

Refactor `CheckOutAction` to debit the source recorded on the visit:
- multiplier by source: WORKSPACE_SUBSCRIPTION → `1.0` (R4); GLOBAL_SUBSCRIPTION → `workspace.hour_multiplier` (R5); FREE → `0.0` (R7).
- Daily cap in REAL minutes (R6):
  - `dailyCapRealMinutes = day_calculation_hours * 60`
  - `usedRealToday = sum(billable_minutes)` of this user's CHECKED_OUT visits at this workspace today (ALL sources)
  - `allowedRealMinutes = min(rawMinutes, max(0, dailyCapRealMinutes - usedRealToday))`
  - `deducted = ceil(allowedRealMinutes * multiplier)`, then clamp to the captured subscription's `remaining_minutes`
  - store `billable_minutes = allowedRealMinutes` (real), `deducted_minutes = deducted` (wallet)
- Lock the captured subscription row (global OR workspace) before deducting. For workspace subs call `DeductWorkspaceSubscriptionAction`; for global keep the existing ledger logic.
- R12 ordering: if the workspace sub's `expires_at` passed during the visit → deduct FIRST, then expire (forfeit remainder + ledger).
- Make `OwnerCheckOutVisitAction` and `AutoCheckOutVisitJob` go through this same `CheckOutAction` (they mostly already do — keep it).

Also: wherever code currently gates on `plan_tier_snapshot !== FREE` (e.g. the checkout-approval guard added earlier), change it to `billing_source !== FREE`.

✅ Verify (CRITICAL — billing): unit tests in `tests/Unit/Attendance/`:
- workspace sub, 60-min visit, multiplier ignored → remaining drops by exactly 60, ledger row written.
- daily cap: stay 11h at an 8h-cap workspace → `billable_minutes`=480, deducted respects cap.
- global sub still deducts with the workspace multiplier (existing tests pass unchanged).
Run `php artisan test`.

---

# PHASE 7 — OWNER PORTAL TAB "الاشتراكات الخاصة"

Read first: `resources/views/workspace/visits/index.blade.php`, `app/Http/Controllers/Web/WorkspaceVisitController.php`, `resources/views/workspace/clients/partials/table.blade.php` (AJAX pattern), `routes/web.php`.

Create `app/Http/Controllers/Web/WorkspaceSubscriptionController.php` (thin → Actions) and routes under the existing `workspace.` group (middleware `workspace.owner`). Policy-scope everything to `Auth::user()->ownedWorkspace`.
Pages/sections (match the existing green RTL portal style; reuse the `PLAN_*` wireframe layout):
- Plan templates list + create/edit/deactivate form.
- "Issue subscription": pick plan, delivery = generate code (default) OR assign by phone.
- Issued subscriptions table: visitor, remaining/total hours, days left, status badge, code.
- "Expiring in 3 days" amber card with a WhatsApp reminder link `https://wa.me/<visitor_phone>`.
- Metric cards: active count, expiring-3-days count, hours sold, templates count.
Add the tab link to the portal nav. Reuse the `vendor.pagination.upwork` paginator.

✅ Verify: log in as a workspace owner, open the tab, generate a code, assign by phone (registered user), see the issued list and the expiring card. `php artisan test --filter=WorkspaceSubscription`.

---

# PHASE 8 — SCHEDULED EXPIRY JOB

Read first: `app/Console/Commands/AutoCheckOutCommand.php`, `bootstrap/app.php` (the `withSchedule` block).

Create command `workspace-subscriptions:expire` calling `ExpireWorkspaceSubscriptionsAction`. Register it in `bootstrap/app.php` `->everyFifteenMinutes()->onOneServer()->withoutOverlapping()`.

✅ Verify: feature test — an ACTIVE sub past `expires_at` (and NOT funding an open visit) becomes EXPIRED with a forfeit ledger row; one funding an open visit is left alone. `php artisan test --filter=Expire`.

---

# PHASE 9 — REPORTS SEPARATION (R13)

Read first: `app/Http/Controllers/Web/WorkspaceFinancialController.php` and any settlement/reporting code that sums visit minutes.

Wherever financial/settlement reports aggregate visits, split totals by `billing_source` (workspace vs global vs free). Do not merge workspace-plan revenue into global numbers. Add a column/section showing workspace-subscription consumption separately.

✅ Verify: the financial page shows workspace-plan minutes separate from global. Existing financial tests stay green.

---

# PHASE 10 — MOBILE (Flutter)

Read first: `lib/features/home/...` (entity, model, repository, cubit), `PLAN_activation_codes_and_subscription_enhancements.md` (balance-card pattern), the activation/redeem screen if present.

- Same single "Scan QR" button — DO NOT add a new scanner (R14).
- Code redeem screen: accept `WSP-` codes too; on success show "صالح في <workspace> فقط" with plan + remaining hours + days.
- Check-in confirmation + home session banner: show the funding source + balance from the check-in response (`billing_source`, workspace sub `remaining_minutes`/`expires_at`). Display only.
- Add all new strings to `lib/l10n/intl_ar.arb`, `intl_en.arb`, `intl_tr.arb` AND the generated `lib/generated/l10n.dart` + `lib/generated/intl/messages_*.dart` (follow how existing keys are added).

✅ Verify: `flutter analyze` clean.

---

# FINAL CHECKLIST (run all, everything must pass)

```
cd laravel-backend
vendor/bin/pint
vendor/bin/phpstan analyse
php artisan migrate:fresh --seed   # must run clean
php artisan test                   # ALL green, including old tests
cd ..
flutter analyze
```

Do not consider the feature done until every box above is checked and the full backend + Flutter checks pass. If you cannot run a command, STOP and say so — do not claim success without running it.
