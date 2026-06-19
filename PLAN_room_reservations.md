# Implementation Plan — Room Reservations (owner-only)

## What this is (and is NOT)
- A **standalone owner tool** to reserve named rooms (e.g. "غرفة صامتة", "غرفة اجتماعات") for a client, for fixed hours on a date, at a per-room hourly price.
- It is a **helper to avoid double-booking** the same room, and a way to record room revenue.
- It is **completely independent** of: global subscriptions, workspace (special) subscriptions, QR check-in, and minute balances. **No balance is ever consumed.** Rooms have nothing to do with plans.
- **Owner-only.** No app-user involvement. The client is just a **name (+ optional phone)** the owner types.
- **No mobile app changes.** Owner web portal only.

---

## Confirmed rules
- R1. Rooms are not linked to any subscription or QR attendance. Zero balance impact.
- R2. Only the workspace owner creates rooms and reservations.
- R3. Each room has a **default hourly price** set by the owner.
- R4. Reservation cost = duration × room hourly price (prorated by the minute: 1.5h × 50 = 75.00).
- R5. The same room cannot be reserved for **overlapping times** (double-book guard). Back-to-back is allowed (10–12 then 12–14 is fine).
- R6. A reservation appears as a row in **both** existing tables — "العملاء والزوار" (clients) and "تسجيل الزوار → أحدث الزيارات المنتهية" (recent visits) — with its cost **added into each table's revenue total** and hours total.
- R7. The badge/plan label for these rows is **"اشتراك مساحة"** (as requested).
- R8. Recurring reservations are supported (e.g. every Sunday 4–6pm).

---

## Data model (3 new tables, nothing touched on existing tables)

`workspace_rooms`
- `id` uuid, `workspace_id` FK, `name`, `hourly_price_cents` unsigned int, `is_active` bool (default true), `position` int, timestamps.
- Index `(workspace_id, is_active)`.
- Never hard-deleted if it has reservations — deactivated instead.

`room_clients` — a reusable client registry per workspace (NEW, per your request)
- `id` uuid, `workspace_id` FK, `name`, `phone`, `note` nullable, timestamps.
- **Unique `(workspace_id, phone)`** — phone is the identity. First booking auto-creates the client; later bookings reuse it so the owner never re-enters the data.
- Gives each client a history: list all their reservations ("treatments") on a client detail page.
- Separate from app `users` and from `workspace_walk_ins` (rooms are fully independent).

`room_reservations`
- `id` uuid, `workspace_id` FK, `room_id` FK (restrict delete), `room_client_id` FK (the registered client).
- `client_name`, `client_phone` snapshots (kept on the row too, so list rendering needs no extra join).
- `starts_at` datetime, `ends_at` datetime (half-open: end exclusive).
- `hourly_price_cents` (snapshot of the room price at booking time), `total_cost_cents` (computed at save).
- `status` string enum `RESERVED | CANCELLED`.
- `note` nullable, `created_by_owner_id` FK.
- Recurrence: `series_id` uuid nullable (rows sharing a series), timestamps.
- Indexes: `(room_id, status, starts_at)` for the overlap check, `(workspace_id, starts_at)` for the lists, `(room_client_id)` for client history.

## Booking by name + number (auto-register client)
On creating a reservation, inside the same transaction:
1. Look up `room_clients` by `(workspace_id, phone)`.
2. If found → reuse it (optionally update the name if the owner typed a new one).
3. If not found → create it (name + phone). The owner just typed the data once.
4. Link the reservation to that `room_client_id` and snapshot name/phone onto the reservation.
The reservation form autocompletes from existing clients as the owner types the phone, so repeat clients are one tap.

---

## How double-booking is prevented ("hold it well")
On create, inside a `DB::transaction`:
1. Row-lock the room (`SELECT … FOR UPDATE`).
2. Reject if any `RESERVED` reservation on that room overlaps the new range, using the half-open rule: conflict iff `existing.starts_at < newEnd AND existing.ends_at > newStart`.
3. Insert if clear; otherwise return a clean "هذه الغرفة محجوزة في هذا الوقت" error.

This serializes bookings per room so two tabs can't double-book the same minute. (MySQL has no exclusion constraints, so this lock+check is the correct pattern.)

---

## Owner UI (new nav tab "حجوزات الغرف")
1. **Rooms manager**: add/edit/deactivate rooms, each with a name + default hourly price.
2. **New reservation form**: pick room → client name (+ optional phone) → date → start time → **duration** (hours/minutes) → optional note. Live cost preview = duration × room price. On submit, the overlap guard runs.
   - **Recurrence** (optional on the form): repeat weekly on chosen weekday(s) until an end date. The series is expanded into individual reservation rows for a rolling ~12-week horizon, each overlap-checked.
3. **Today / upcoming reservations list** per room, with cancel.
4. A small day view per room is a nice-to-have (phase 2); the form + list is the MVP.

---

## Integration into the two existing tables (the main effort)
Reservations must appear in, and add to the totals of:
- `WorkspaceClientController@index` (clients table) — currently a UNION of users + walk-ins.
- `WorkspaceVisitController@index` (recent ended visits table).

Approach: add reservations as a **third source** merged into each table's query/summary so pagination and the bottom totals stay correct:
- Clients table: add a UNION branch grouping reservations by `client_name`+`client_phone` (count = number of reservations, hours = Σ duration, revenue = Σ `total_cost_cents`, badge = "اشتراك مساحة", آخر زيارة = latest `starts_at`).
- Recent visits table: add reservation rows (الزائر = client name, الباقة = "اشتراك مساحة", الدخول = `starts_at`, الخروج = `ends_at`, المدة = duration, دقائق مخصومة = "—", الإيراد = `total_cost`), and add their cost + hours to the summary footer (the "362.53 ساعة / 7,232.54 ج.م" totals).
- Date filters on those pages also filter reservations by `starts_at`.
- `CANCELLED` reservations are excluded from lists and totals.

This is the riskiest part because it refactors the two existing summary queries — it will be done carefully so the current visit numbers don't change, only reservations are added on top.

---

## Delivery order
1. Migrations + models + factories (`workspace_rooms`, `room_reservations`) — independent, safe.
2. Domain: repos + actions (`CreateRoom`, `UpdateRoom`, `DeactivateRoom`, `CreateReservation` with overlap guard + recurrence expansion, `CancelReservation`).
3. Owner tab: rooms manager + reservation form + list + routes (owner-scoped policies).
4. Integration into the clients + recent-visits tables and their totals.
5. Tests: overlap rejection, back-to-back allowed, cost math, recurrence expansion + per-occurrence conflict handling, totals include reservations, cross-owner denial.

No changes to global/workspace subscriptions, QR, checkout, or the mobile app.

---

## POINTS I NEED YOU TO CONFIRM before I start
1. **Recurrence conflict behavior** — when one date in a recurring series clashes with an existing booking, should I **skip that date and book the rest** (and show you which were skipped), or **refuse the whole series** until you change the time? (I recommend skip-and-report.)
2. **Badge label** — you asked for "اشتراك مساحة" on reservation rows. That label currently means a workspace-subscription visit, so reservations and subscription visits will look identical in the list. Keep "اشتراك مساحة" as requested, or use a distinct "حجز غرفة" badge so you can tell them apart? (I recommend "حجز غرفة".)
3. **Cost rounding** — prorate by the minute (1.5h × 50 = 75.00) ✔ recommended, or charge whole hours only (round up to 2h)?
4. **Where the tab lives** — a new top-nav tab "حجوزات الغرف", or a section inside the existing "الاشتراكات الخاصة" tab? (I recommend a separate tab, since rooms are unrelated to subscriptions.)

> Resolved per your message: phone is now **required** (it is the client identity / dedup key), and clients are **auto-registered** into `room_clients` on first booking and reused afterwards with a per-client booking history.
