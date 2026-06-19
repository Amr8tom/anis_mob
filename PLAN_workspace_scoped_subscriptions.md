# Agent Prompt — Workspace-Scoped Subscriptions (owner-issued, single-workspace plans)

Senior Laravel + Flutter engineer on **Anis**. Follow `laravel-backend/feature_template_for_laravel_.md` and `FEATURE_TEMPLATE.md` exactly.

## Goal
Let each workspace owner create their own plans (X hours, Y days) that are valid **only at their own workspace** (same existing QR token), issue them to visitors, and see a watchlist of subscriptions expiring within 3 days — all in a new owner-portal tab "الاشتراكات الخاصة".

## Key architectural decision (do NOT overload the global tables)
The global `subscriptions` table enforces `unique(user_id, active_flag)` = **one active subscription per user globally**. A visitor may hold a global plan AND several workspace-scoped plans at once, so workspace-scoped subscriptions MUST be a **separate concept**, not rows in `subscriptions`. Add two new tables instead:

### `workspace_plans` (owner-defined templates)
`id` uuid, `workspace_id` FK→workspaces (cascade), `name`, `included_minutes` int (hours×60), `duration_days` int, `price_cents` nullable, `is_active` bool, timestamps. Index `(workspace_id, is_active)`.

### `workspace_subscriptions` (a visitor's entitlement at one workspace)
`id` uuid, `workspace_id` FK, `workspace_plan_id` FK, `user_id` nullable FK (null until a code is activated), `status` string enum `PENDING_ACTIVATION|ACTIVE|EXPIRED`, `started_at` nullable, `expires_at` nullable, `remaining_minutes` int nullable, `active_flag` tinyint nullable, timestamps. Enforce one active per (user,workspace): `unique(user_id, workspace_id, active_flag)`. Indexes `(workspace_id, status, expires_at)` for the expiry watchlist, `(user_id, workspace_id, status)` for check-in lookup.

### `workspace_subscription_codes` (delivery)
Mirror the existing `PlanActivationCode` pattern (`GeneratePlanCodeAction`) but workspace-scoped: `code` unique, `workspace_plan_id` FK, `workspace_id` FK, `status UNUSED|REDEEMED|REVOKED|EXPIRED`, `redeemed_by` FK user nullable, `workspace_subscription_id` nullable, `expires_at` nullable, timestamps. Reuse the readable `XXXX-XXXX` generator.

Add a workspace-scoped ledger or reuse `subscription_ledgers` with a `workspace_subscription_id` nullable column for the audit trail (deductions/forfeits) — keep money/time changes audited per template §6.2.

## Check-in / billing change (the critical path)
`CheckInAction` currently resolves the global active subscription. New resolution order at workspace X:
1. If the visitor has an `ACTIVE`, non-expired `workspace_subscription` for (user, X) with `remaining_minutes > 0` → use it.
2. Else fall back to the existing global subscription logic (unchanged).
3. Else apply the existing free/blocked rules.
`CheckOutAction` must deduct from **the same source captured on the visit**. Add `workspace_subscription_id` (nullable) to `workspace_visits` and set it at check-in when a workspace sub is used; checkout deducts from whichever source the visit recorded (global `subscription_id` OR `workspace_subscription_id`), inside the row-locked transaction, writing a ledger row. Keep the daily-cap and multiplier logic.

## Owner web — new tab "الاشتراكات الخاصة" (see wireframe)
- Plan templates CRUD (name, hours, days, optional price, active toggle).
- "Issue a subscription": choose plan, delivery = **generate activation code** (default, reuse code pattern) OR **assign by phone** (creates an ACTIVE workspace_subscription bound to that user immediately, like the existing owner walk-in flow).
- Issued list: visitor, remaining hours/total, days left, status badge (active/expiring/pending/expired), code.
- **Expiring-soon card**: `workspace_subscriptions` where status ACTIVE and `expires_at` between now and now+3 days, highlighted amber, with a WhatsApp "تذكير" deep-link (owner already stores `admin_phone`; build `https://wa.me/<visitor_phone>`).
- Metric cards: active count, expiring-in-3-days count, hours sold, plan templates count.
- Controllers thin → Actions; Policies scope every action to `Auth::user()->ownedWorkspace`.

## Lifecycle job
`workspace-subscriptions:expire` scheduled every 15 min (mirror `AutoCheckOutCommand` / `bootstrap/app.php` schedule): flip ACTIVE→EXPIRED when `expires_at <= now()` (forfeit remaining minutes with a ledger row), clear `active_flag`. The "expiring in 3 days" list is a query, not a job, but optionally fire a daily owner notification/email digest.

## Scan & check-in resolver (CONFIRMED — same single "Scan QR" button)
No new scanner, no new button, no source-picker. The visitor scans the workspace's existing QR exactly as today; the server decides the funding wallet. `CheckInAction` resolves the source in this fixed priority and records it on the visit:
1. Active, non-expired `workspace_subscription` for (user, this workspace) with `remaining_minutes > 0` → use it (auto-priority).
2. Else the existing global subscription logic (unchanged).
3. Else the existing free / blocked rules.
Set `workspace_visits.workspace_subscription_id` when (1) wins, otherwise the global `subscription_id`. Checkout debits whichever the visit recorded.
- **Multiplier rule (CONFIRMED):** workspace subscriptions deduct at multiplier `1.0` (1 attendance hour = 1 plan hour). The workspace `hour_multiplier` applies only to global plans. The daily cap (`day_calculation_hours`) still applies to workspace subs, unchanged and workspace-scoped.
- **No source-picker in v1.** Auto-priority (workspace plan first) is final for v1; only add a one-time post-scan picker later if users explicitly ask to "save" workspace hours and pay with the global plan.
- Surface the chosen source + balance in the check-in response so the app can display "باقة المكان: X ساعة · ينتهي خلال Y يوم" (display only).

## Mobile (Flutter)
- Activation: extend the existing code-redeem screen (or add one) to accept workspace codes; on success show "صالح في <workspace> فقط".
- Home/profile balance display: when checked in at a workspace where a workspace_subscription is the active source, show its remaining hours/days (the attendance/profile resources should expose the active source). Reuse the balance-card pattern from `PLAN_activation_codes_and_subscription_enhancements.md`.
- Defensive parsing; ar/en/tr strings.

## Quality gates
Per templates: Pint + Larastan L8 clean, `php artisan test` green, reversible migrations, factories/seeders, no N+1, policy-gated + throttled routes, all balance changes audited in-transaction. Feature tests: issue code, redeem at correct workspace, redeem rejected at a different workspace, check-in prefers workspace sub, checkout deducts from the visit's recorded source, expiry job forfeits + flips, expiring-in-3-days list, cross-owner denial. Flutter: analyze clean + cubit tests.

## Open decisions (confirm with product owner)
1. Delivery default — activation code (recommended, matches existing system) vs assign-by-phone. Plan supports both.
2. Can a visitor stack a workspace plan on top of a global plan at the same workspace? Recommended: at a given workspace, the workspace plan takes priority and the global plan is untouched.
3. Reminder channel for the 3-day watchlist: in-portal list only (v1) vs WhatsApp deep-link (recommended) vs automated push/SMS (later).
