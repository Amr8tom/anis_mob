# Anis — Workspace Owner Portal: Feature Review, Rating & Benchmark

*Scope: the web portal used by workspace/center owners (not the student app). Based on the actual routes, controllers, and settings in `laravel-backend`.*

---

## 1. What the portal does today (inventory)

**Onboarding & access**
- Self-registration → admin review (`pending`) gate; token-based invitations; login/logout.
- Dedicated `workspace_owner` auth guard (owners are separated from app users).

**Analytics dashboard** (period filters: today / 7d / 30d / month / custom)
- Revenue split across 4 streams: visits, room reservations, private sessions, subscriptions.
- Billable hours that respect the **daily cap** (`day_calculation_hours`) and the workspace `hour_multiplier`.
- Unique visitors, active-now, visit trend chart, top clients, top rooms, top private sessions.
- Education breakdown by teacher & subject.
- Notification open/click rates; low-hour & expiring-subscription watchlists.

**Workspace settings**
- Profile: name, description, address, geo (lat/long), capacity, open/close time, admin phone.
- Operations: status (OPEN/BUSY/FULL/CLOSED), manual occupancy, daily cap hours, **checkout mode (direct vs approval)**.
- Pricing: `hour_multiplier`; drinks menu with per-item prices.
- Media: cover image + gallery.

**Operations modules**
- **Visits / check-in–out:** live check-in, walk-ins, automatic time-based billing, checkout-approval flow.
- **Rooms:** CRUD, hourly pricing, reservations with recurrence + overlap guard, room-client registry, cancellations.
- **Public study sessions:** CRUD, listed in the app catalog.
- **Private sessions:** owner-created, hidden from the catalog; attendees, **bulk import**, QR check-in, finish/cancel.
- **Education center setup:** teachers, subjects, grade levels (with enable/disable toggles).
- **Workspace-scoped subscriptions:** plans CRUD, generate/revoke codes, assign by phone, cancel; expiry watchlist.
- **Clients (CRM-lite):** list + detail with lifetime visits/minutes.
- **Notifications:** targeted push campaigns, user/session search, preview, send, delivery + open/click tracking.
- **Financials:** revenue & settlement view (platform settles with owner).
- **Localization:** Arabic / English / Turkish.

**Platform/admin side** (not owner-facing): approve/reject/suspend, settlements, billing config, QR regenerate, owner change.

---

## 2. Rating

| Area | Score (/10) | Notes |
|---|---|---|
| Check-in / attendance | **9** | QR + walk-ins + approval flow + auto-billing is excellent and ahead of most niche tools. |
| Room booking | **8** | Recurrence, overlap guard, per-minute cost. Missing: member self-booking, deposits, waitlist. |
| Analytics / reporting | **8** | Genuinely rich multi-stream revenue + ops. Missing: export (CSV/PDF), expenses/P&L, cohort retention. |
| Subscriptions / plans | **8** | Workspace-scoped + global, codes, expiry watchlist. Missing: auto-renew/recurring billing. |
| Education-center fit | **6.5** | Teachers/subjects/grades/private sessions are a strong base. Missing: teacher payouts, parent comms, progress. |
| Billing & payments | **4** | Tracks revenue & settlements but **no online payment collection, invoices/receipts, or recurring billing**. |
| Staff & permissions | **3** | Single owner login; no receptionist/staff roles or audit log. |
| Member self-service | **5** | Strong owner-side; users can't self-book rooms or buy plans in-app yet. |
| **Overall** | **7.2 / 10** | A strong, well-architected operations portal; the gaps are in money-movement, staffing, and self-service. |

**Bottom line:** the *operations* core (attendance, rooms, sessions, analytics) is genuinely strong — in several areas better than off-the-shelf niche tools because it's purpose-built for the Egyptian study-space/center model. The weak spots are the "business back-office" layer (payments, invoicing, staff roles) that mature coworking/education platforms treat as table stakes.

---

## 3. Benchmark vs other systems

**Coworking management software** (Nexudus, OfficeRnD, Cobot, Optix, andcards, archie)
- *They have, you don't yet:* automated recurring billing + online payments, invoices/receipts, member self-service booking & sign-up, staff roles, door/access-hardware integration, accounting/Stripe integrations, contracts, day-pass/credits automation.
- *You have, they often charge extra for or lack:* purpose-built QR attendance with per-minute auto-billing, checkout-approval workflow, a localized Arabic-first owner portal, and a built-in student-discovery app (their "marketplace" reach is weaker).

**Education/tuition-center management** (TeachWorks, Classe365, local center systems)
- *They have, you don't yet:* tutor/teacher payroll & revenue-share payouts, parent communication, student progress/grades, lead pipeline, automated lesson reminders, recurring tuition invoicing.
- *You have:* session/attendance + QR + bulk import + teacher/subject/grade modeling — a solid operational base they'd recognize.

**What both categories assume as standard that Anis is missing:** online payment collection, invoices/receipts (with tax), multiple staff accounts with permissions, and member-facing self-service.

---

## 4. Recommendations (prioritized)

### Tier 1 — highest impact (close the back-office gap)
1. **Online payments + receipts (Egypt-first):** integrate Paymob / Fawry / InstaPay so owners collect plan payments and room deposits in-app, with auto-generated receipts. This is the single biggest gap vs every competitor.
2. **Recurring / auto-renew memberships:** monthly seat or hours plans that bill automatically (not just code redemption), with dunning for failed payments.
3. **Staff accounts & roles:** add a "receptionist/staff" login under the owner with scoped permissions (check-in only, no financials) + an **audit log** of who did what. Centers and busy spaces can't run on one shared owner login.
4. **WhatsApp + SMS notifications:** in Egypt WhatsApp beats push for reminders, receipts, and expiry alerts. Add it alongside the existing push campaigns.

### Tier 2 — strong differentiators
5. **Teacher/tutor payout engine (education centers):** you already track sessions per teacher — add revenue-share %/fixed-rate payout calculation and a payout report. This wins education centers specifically.
6. **Member self-service:** let students book rooms and buy/renew plans from the app (rooms are owner-only today). Reduces front-desk load.
7. **Expense tracking + true P&L:** financials are revenue-only; add expenses (rent, salaries, utilities) for a real profit view, and **CSV/PDF export** of all reports for accounting.
8. **No-show & deposit handling for rooms/sessions:** reservation deposits, no-show flags, and waitlists — protects revenue on private rooms.

### Tier 3 — polish & retention
9. **Egyptian e-invoice (ETA) compliance** for owners who are registered businesses.
10. **Loyalty / referrals & reviews:** streak-based perks, "invite a friend," and post-visit ratings feed both retention and the marketing engine.
11. **Multi-branch / chain support:** let one owner manage several workspaces from one account (today it's one owner → one workspace).
12. **Lightweight POS + stock for the drinks menu:** you already price drinks — add sell + stock-deduct so the café/snacks line is countable in revenue.
13. **Capacity/occupancy automation:** auto-flip status to BUSY/FULL from live check-ins instead of manual occupancy.

---

## 5. Suggested next build
If you want the highest ROI first: **(1) Paymob/Fawry payments + receipts**, then **(3) staff roles + audit log**, then **(5) teacher payouts**. Those three move Anis from "great operations tool" to "full business platform" and directly remove the top objections an owner comparing you to Nexudus/TeachWorks would raise.
