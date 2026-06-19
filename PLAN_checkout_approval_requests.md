# Agent Prompt — Checkout Approval Requests (user "request to leave" → owner approves)

You are a senior Laravel + Flutter engineer on the **Anis** app.

**MANDATORY — read and follow exactly:**
- `laravel-backend/feature_template_for_laravel_.md` (Domain Actions, Repository interfaces, DTOs, Form Requests, Resources, Policies, enums, row-locked transactions, ledger audit, `{success,data,message}` envelope, tests, Pint + Larastan L8).
- `FEATURE_TEMPLATE.md` (Flutter Clean Architecture: domain entities + use cases + repo interface; data models + remote/local sources + repo impl; presentation Cubit + thin widgets; read-with-offline-cache, write-online-first; domain never imports data/UI).

**Read before coding (verified to exist):**
- API: `app/Domain/Attendance/Actions/CheckOutAction.php`, `OwnerCheckOutVisitAction.php`, `EloquentAttendanceRepository.php`, `app/Http/Controllers/Api/V1/WorkspaceVisitController.php`, `app/Http/Controllers/Web/WorkspaceVisitController.php`, `app/Http/Resources/WorkspaceVisitResource.php`, `app/Models/Workspace.php`, `app/Models/WorkspaceVisit.php`, `routes/api.php`, `routes/web.php`.
- Owner UI: `resources/views/workspace/visits/index.blade.php` (the "الحاضرون الآن" active table), `resources/views/workspace/settings.blade.php`.
- Flutter: `lib/features/home/domain/{entity/workspace_attendance_entity.dart,repository/workspace_attendance_repository.dart,use_cases/check_out_workspace_use_case.dart}`, `data/{model/workspace_attendance_model.dart,data_sources/workspace_attendance_remote_data_source.dart,repositories/workspace_attendance_repository_impl.dart}`, `presentation/controller/home_cubit.dart`, `presentation/widgets/workspace_session_banner_widget.dart`, `core/constants/api_constants.dart`.

---

## Business goal

Today every user checks out directly (`POST /workspace-visits/{visit}/check-out`). We add an **owner-controlled approval gate** for checkout:

- Each workspace owner chooses a **checkout mode** in settings:
  - **DIRECT** (default — preserves current behavior): users check out themselves instantly.
  - **APPROVAL**: paid users cannot self-checkout; they send a **"request to leave"**, and the owner approves it from the dashboard, which performs the real checkout.
- **Free-plan visits always check out directly**, regardless of mode. The gate exists to control **paid** (SILVER/GOLD) checkouts, because those deduct balance and the owner wants to confirm the person actually left. (Assumption — flag to product owner: if they want free users gated too, it's a one-line change in the policy check.)
- In the owner's active-visitors table, rows where a user has **requested to leave** are **highlighted** and **sorted to the top** so the owner notices and acts FIFO.

---

## Backend (Laravel)

### 1. Migrations
- `workspaces`: add `checkout_mode` — string column (NOT MySQL enum), default `'DIRECT'`. Back it with a PHP enum `App\Enums\CheckoutMode { DIRECT, APPROVAL }`, cast on the model.
- `workspace_visits`: add
  - `checkout_requested_at` timestamp nullable
  - `checkout_request_note` string nullable (optional future use)
  - index `(workspace_id, status, checkout_requested_at)` to make the "pending requests first" query cheap.
- Reversible; update `WorkspaceFactory` defaults (`checkout_mode => 'DIRECT'`, `checkout_requested_at => null`).

Keep `VisitStatus` as `CHECKED_IN | CHECKED_OUT`. A pending request is a `CHECKED_IN` visit with `checkout_requested_at != null` (no new status value — avoids a migration of the status enum and keeps existing billing/auto-checkout logic intact).

### 2. Model
- `WorkspaceVisit`: cast `checkout_requested_at` datetime; add helper `bool hasPendingCheckoutRequest()` (`status === CHECKED_IN && checkout_requested_at !== null`) and scope `pendingCheckoutRequests()`.
- `Workspace`: cast `checkout_mode` to `CheckoutMode`; helper `requiresCheckoutApproval(): bool`.

### 3. Domain/Attendance — new action `RequestCheckoutAction`
Inside `DB::transaction` with `lockForUpdate` on the visit (ownership-scoped, reuse `lockVisitForUser`):
1. Visit not found / not owned → 404.
2. Visit already `CHECKED_OUT` → 409 (`VisitAlreadyClosedException`).
3. If the visit's plan tier is FREE **or** the workspace mode is DIRECT → do **not** create a request; throw a domain signal the controller maps to "just check out directly" (or return a flag). Simplest: controller decides (see routing). Action only runs when approval is actually required.
4. Idempotent: if `checkout_requested_at` already set, return the visit unchanged (no error).
5. Set `checkout_requested_at = now()`. Structured log `visit.checkout_requested`.
6. Return the visit. **No balance change here** — minutes are only deducted at the real checkout (owner approval).

Optional `CancelCheckoutRequestAction` — clears `checkout_requested_at` (user changed their mind). Idempotent.

### 4. API routes (`routes/api.php`, under `auth:sanctum` + `throttle:api`)
- `POST /workspace-visits/{visit}/request-checkout` → `requestCheckout`
- `DELETE /workspace-visits/{visit}/request-checkout` → `cancelCheckoutRequest` (optional)
- Keep `POST /workspace-visits/{visit}/check-out`, but **modify guard**: if the workspace `requiresCheckoutApproval()` AND the visit is non-FREE AND `checkout_requested_at` is not "owner-approved context" → reject self-checkout with 403 (`CheckoutRequiresApprovalException`) telling the client to use the request endpoint. (Owner approval continues to go through the **web** `OwnerCheckOutVisitAction`, which is unaffected.)

### 5. Controller (`Api/V1/WorkspaceVisitController`)
- `requestCheckout`: resolve the active visit, branch:
  - If free tier or workspace mode DIRECT → call `CheckOutAction` and return the closed visit (so the app's single button "just works").
  - Else → call `RequestCheckoutAction`, return the visit with `checkout_requested_at` set (202/200).
- Policy: `WorkspaceVisitPolicy@requestCheckout` — the authenticated user owns the visit. Cross-user denial test.

### 6. Resource (`WorkspaceVisitResource`) — additive only
Add fields the app needs to pick the right button and show pending state:
- `checkout_mode` (workspace mode)
- `checkout_requested_at` (ISO or null)
- `can_check_out_directly` (bool) = `tier === FREE || workspace.checkout_mode === DIRECT`
- `is_checkout_pending` (bool) = `status CHECKED_IN && checkout_requested_at != null`
Make sure the `active` endpoint eager-loads the workspace so `checkout_mode` is present with no N+1.

### 7. Owner web — settings toggle
- `WorkspaceSettingsController@update` + `WorkspacePortal/WorkspaceUpdateRequest` + `WorkspaceUpdateData` + `UpdateWorkspaceAction`: accept `checkout_mode in:DIRECT,APPROVAL`.
- `settings.blade.php`: in a sensible tab (e.g. "الحالة المباشرة" or a new "الخروج" section) add a radio/toggle:
  - "السماح للزوار بتسجيل الخروج مباشرة" (DIRECT)
  - "يجب أن يطلب الزائر الإذن قبل الخروج وأوافق أنا" (APPROVAL)
  - Helper text explaining it applies to الباقات المدفوعة only.

### 8. Owner web — active visitors table (`visits/index.blade.php`)
- In the controller, split/sort `$activeVisits`: **pending requests first**, ordered by `checkout_requested_at` ascending (oldest request on top = FIFO), then the rest by `check_in_at` desc.
- Highlight pending rows: amber/orange background (e.g. `background:#fff8e6; border-right:4px solid #f4a800;`) — distinct from the green theme so it's instantly noticeable. Add a pulsing badge "🔔 طلب خروج — منذ X" using `checkout_requested_at->diffForHumans()`.
- The action cell for a pending row becomes a prominent **"الموافقة وتسجيل الخروج"** button (green, posts to the existing `workspace.visits.checkout` route → `OwnerCheckOutVisitAction`, unchanged). Non-pending rows keep the normal "تسجيل خروج".
- Show a count in the header: "الحاضرون الآن (4) — منهم 2 بانتظار الموافقة على الخروج".
- Add lightweight polling so requests appear without manual refresh: a small JS `setInterval` that re-fetches the page (or, better, an AJAX partial like the clients table already uses) every ~20s and swaps the active-table partial. Extract the active table into `visits/partials/active-table.blade.php` and serve it on `X-Requested-With` like `WorkspaceClientController` does.

### 9. Tests
- Unit: `RequestCheckoutAction` (idempotent; no balance change; sets timestamp), guard on `CheckOutAction` path.
- Feature: request-checkout happy path (APPROVAL+paid → pending), free tier in APPROVAL mode → checks out directly, DIRECT mode → checks out directly, self-checkout blocked 403 in APPROVAL+paid, cross-user 403, owner approval closes the visit + deducts (existing OwnerCheckOut path), resource exposes new flags. Assert envelope + statuses.

---

## Flutter

### Entity / model / data
- `WorkspaceAttendanceEntity`: add `checkoutMode` (enum or string), `checkoutRequestedAt` (DateTime?), `canCheckOutDirectly` (bool), `isCheckoutPending` (bool getter from `checkoutRequestedAt != null && checkOutTime == null`). Update `props`, model `fromJson`, and the local cache mapping. Defensive parsing (missing fields default to direct/false to preserve old behavior).
- New use case `RequestCheckoutUseCase` + repository method `requestCheckout(...)` + remote source `POST /workspace-visits/{id}/request-checkout`. Add the URL to `api_constants.dart`. Optional `cancelCheckout`.

### Home cubit + button logic
- The checkout button (currently a single "check out") becomes mode-aware:
  - `canCheckOutDirectly == true` → call `CheckOutWorkspaceUseCase` (current behavior) → show checkout summary.
  - else → call `RequestCheckoutUseCase` → emit a **pending** state.
- Pending state: the green session banner switches to an **amber "طلب خروج قيد الانتظار"** banner with text "بانتظار موافقة المكان على تسجيل خروجك" and a subtle spinner; button changes to "إلغاء الطلب" (if cancel implemented) or disabled.
- **Detect approval:** while pending, poll `GET /workspace-visits/active` (the cubit already has the active-visit concept) every ~20–30s; when the server returns the visit as `CHECKED_OUT` (or active becomes null), transition to the checkout-summary state showing deducted minutes / remaining balance, and refresh home profile balance.
- All strings localized in `intl_ar.arb` / `intl_en.arb` (+ tr). States for loading/success/error/pending/offline.

### Banner widget
- Update `workspace_session_banner_widget.dart` to render two visual variants driven by `isCheckoutPending`: the existing green "session running" banner, and the new amber "checkout requested — awaiting approval" banner.

### Flutter tests
- Cubit: direct mode → checkout; approval+paid → request then pending; poll detects approval → summary. Repository/use case unit tests.

---

## Delivery order & gates
1. Backend migration + enum + model → 2. RequestCheckoutAction + guard + routes + resource → 3. Owner settings toggle → 4. Owner table highlight/sort/poll → 5. Flutter entity/usecase/cubit/banner.
2. Backend gates: Pint + Larastan L8 clean, `php artisan test` green, migrations reversible, factories/seeders updated, new routes policy-gated + throttled, no N+1 (eager-load workspace on active), no balance change in request action, existing checkout/billing/auto-checkout tests still green.
3. Flutter gates: `flutter analyze` clean, domain isolation preserved, ar/en strings, `flutter test` green.
4. **Do not break the DIRECT path** — it is the default and must behave exactly as today. Additive API fields only.

## Open decision (confirm with product owner)
- Scope of the gate: this plan gates **paid** visits only; free visits always direct. If free should also be gated when mode = APPROVAL, change the `can_check_out_directly` rule to depend only on `checkout_mode`.
