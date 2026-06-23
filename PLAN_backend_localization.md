# Implementation Plan — Multi-Language Laravel Backend (ar + en + tr, easily extensible)

> **v2 — merged optimal.** This merges this plan with a second proposal. The two
> agreed on ~90% (config registry, SetLocale middleware, lang files, RTL/LTR,
> translated validation, a switcher, infra-first incremental rollout, `tr` not
> `turkey`, never use Arabic text as a key). Below are the **decisions where one
> approach is strictly better**, then the full plan.

## Comparison & final merged decisions
| Topic | Plan A (this) | Plan B (other) | **Optimal (chosen)** |
|---|---|---|---|
| API message strategy | `code` + localized `message` | only `Accept-Language` → message | **A** — return BOTH a stable `code` and a localized `message`. The app already has ar/en/tr; the `code` decouples it from server wording, the `message` serves web/3rd parties. |
| API locale source | `X-Locale` header (+ Accept-Language) | `Accept-Language` only | **Both** — accept `X-Locale` (explicit, app-controlled) first, fall back to `Accept-Language` (standard, q-weighted). Superset. |
| Web locale persistence | cookie + DB column | session | **Hybrid** — session for the logged-in portal (simplest), **plus** a cookie so the choice survives on the pre-login pages, **plus** a DB `locale` column when authenticated. |
| Key style | structured PHP keys | JSON for quick UI + PHP arrays | **Structured PHP keys** as the primary convention (cleaner for Arabic-first + future RTL langs); JSON only for sprinkled one-offs. |
| RTL/LTR CSS | logical properties (`margin-inline-start`) | "don't hardcode dir" | **A** — move the portal CSS to logical properties so ONE stylesheet serves both directions (not a separate RTL sheet). |
| View ergonomics | — | `view()->share('currentDirection')` | **Adopt B** — share `currentLocale` + `currentDirection` from the middleware so Blade stays clean. |
| Enums / DB content | enums.php labels; JSON columns later | same | identical — keep. |

Net: take this plan as-is, and additionally adopt B's `view()->share(...)`, the explicit resolution-priority list, the `POST /locale` switcher, and `Accept-Language` q-parsing (all added below).


## Current state (verified)
- No `lang/` directory exists (Laravel 13 ships none by default).
- No locale-resolution middleware.
- `config/app.php`: `locale = en`, `fallback_locale = en`.
- The **workspace web portal** Blade views are hardcoded **Arabic**; the **API** returns a mix of hardcoded Arabic/English strings (e.g. exception messages like `هذه الغرفة محجوزة...`, `ApiResponse` messages).
- The **Flutter app already has its own ar/en/tr** translations — so the app UI is handled; the gap is **backend-originated strings** (API messages/errors + the web portals).

So we are building i18n from scratch. Three surfaces need it:
1. **API responses** (consumed by the app + any 3rd party).
2. **Workspace owner portal** (Blade).
3. **Admin portal** (Blade).

Out of scope for v1 (call out separately): translating **user-generated DB content** (workspace names, room names, plan names/descriptions). Those are data, not UI strings, and need a different mechanism (translatable JSON columns / spatie/laravel-translatable) — a separate phase if you want it.

---

## The optimal architecture (Laravel-native, no heavy packages)

### 1. Locale registry — one source of truth
Create `config/locales.php` so adding a language is pure config, never code:
```php
return [
    'supported' => [
        'ar' => ['name' => 'العربية',  'dir' => 'rtl', 'flag' => '🇪🇬'],
        'en' => ['name' => 'English',  'dir' => 'ltr', 'flag' => '🇬🇧'],
        'tr' => ['name' => 'Türkçe',   'dir' => 'ltr', 'flag' => '🇹🇷'],
    ],
    'default'  => 'ar',   // portal default (business is Arabic-first)
    'fallback' => 'en',
];
```
Keep `config/app.php` `fallback_locale = en`. A small helper `App\Support\Locales` (or just config reads) exposes `supported()`, `isSupported($c)`, `direction($c)`, `isRtl($c)`. **Adding Turkish-plus-N future languages = add a row here + a `lang/{code}` folder. No other code changes.**

### 2. Translation file layout (`lang/`)
Run `php artisan lang:publish` once (gets the framework's `en` validation/auth/pagination files), then create per-locale folders. Split by domain so files stay small and ownership is clear:
```
lang/
  en/ validation.php  auth.php  pagination.php
      errors.php       # API/domain exception messages, keyed by code
      portal.php       # workspace owner portal UI
      admin.php        # admin portal UI
      attributes.php   # field names for validation (:attribute)
      enums.php        # display labels for statuses/tiers/billing sources
  ar/ ... (same files, Arabic)
  tr/ ... (same files, Turkish)
  en.json  ar.json  tr.json   # optional: short free-text strings via __('Sentence')
```
Convention: **structured keys** (`__('portal.rooms.book_button')`) for everything, not English-sentence keys — cleaner for RTL/Arabic-first and for future languages. Reserve JSON only if you prefer sentence-keys for one-off strings.

### 3. Locale resolution — a single `SetLocale` middleware
Resolve in priority order, differing slightly per surface:

**Web (portal + admin):**
1. `?lang=tr` query (explicit switch) → also persists to a cookie.
2. `locale` cookie.
3. Authenticated owner/admin `locale` column (DB preference).
4. `Accept-Language` header (best match against supported).
5. `config('locales.default')`.

**API:**
1. `X-Locale: tr` header (explicit, app-controlled) — recommended primary.
2. Authenticated user's `locale` column.
3. `Accept-Language`.
4. `config('locales.default')`.

The middleware validates against `config('locales.supported')` (never trust raw input), then:
```php
app()->setLocale($code);
\Carbon\Carbon::setLocale($code);
view()->share('currentLocale', $code);                                   // from Plan B
view()->share('currentDirection', config("locales.supported.$code.dir")); // so Blade stays clean
```
For `Accept-Language`, use Laravel's q-weight-aware matcher — `$request->getPreferredLanguage(array_keys(config('locales.supported')))` — so `Accept-Language: tr-TR,tr;q=0.9,en;q=0.8` resolves to `tr`. Register the middleware globally in `bootstrap/app.php` (web + api groups). One class, ~30 lines.

### 4. Persist preference (optional but recommended)
Add a nullable `locale` column to `users` and `workspace_owners` (and admins if separate). Expose:
- API: `PATCH /profile` accepts `locale`.
- Portal: the language switcher writes the cookie **and**, if logged in, the DB column.
This makes the choice sticky across devices/sessions.

---

## Surface-by-surface work

### A. API responses (highest value for the app)
- **Strategy: return BOTH a stable `code` and a localized `message`.** The `message` is translated server-side from the resolved locale; the `code` lets the app map to its own ar/en/tr strings if it prefers. This serves the app, web, and any integrator.
- Refactor `App\Support\ApiResponse` and the `ApiException` hierarchy: replace hardcoded strings with `__('errors.<key>')` and add an `errorCode` (e.g. `ROOM_SLOT_UNAVAILABLE`, `NO_ACTIVE_SUBSCRIPTION`). Envelope becomes:
  ```json
  { "success": false, "code": "ROOM_SLOT_UNAVAILABLE",
    "message": "Bu oda bu saatte zaten dolu.", "data": null }
  ```
- Validation errors already localize automatically once `lang/{locale}/validation.php` + `attributes.php` exist.
- Translate every existing hardcoded Arabic exception/success message into `lang/*/errors.php` keys (CheckIn/CheckOut, subscription, rooms, owner-visit, etc.).

### B. Workspace owner portal (Blade) — the big mechanical piece
- Extract every hardcoded Arabic string in `resources/views/workspace/**` into `lang/{locale}/portal.php` keys, replacing with `{{ __('portal.…') }}` / `@lang`. Do it **page by page** (settings → visits → clients → rooms → subscriptions → financials → auth), each independently shippable.
- **Direction (RTL/LTR):** drive the layout from the locale. In `workspace/layouts/app.blade.php`:
  ```blade
  <html lang="{{ app()->getLocale() }}" dir="{{ config('locales.supported.'.app()->getLocale().'.dir', 'rtl') }}">
  ```
  Refactor the portal CSS to **CSS logical properties** (`margin-inline-start` instead of `margin-right`, `padding-inline-end`, `text-align: start`) so one stylesheet works in both directions. Where you used `border-right` accents, switch to `border-inline-end`.
- **Language switcher** in the portal nav: a small dropdown listing `config('locales.supported')`, posting `?lang=xx` (sets cookie + DB). Reuse the green theme.
- Number/date formatting: use `Carbon` with the set locale; format currency with `Number::currency()` or a helper so "ج.م / EGP / ₺"-style suffixes and digit grouping follow the locale.

### C. Admin portal (Blade)
- Same extraction into `lang/{locale}/admin.php`, same direction handling and switcher. Lower priority than the owner portal if admins are internal/Arabic-only — but do it for completeness and future-proofing.

### D. Enums & dynamic labels
- Never translate stored enum **values** (keep `RESERVED`, `ACTIVE`…). Translate **display labels** via `lang/{locale}/enums.php` (`enums.reservation_status.RESERVED = 'Reserved' / 'محجوز' / 'Rezerve'`), looked up in Blade/Resources. This keeps the DB language-neutral.

---

## Adding a new language in the future (the payoff)
Purely additive checklist — no code edits:
1. Add the row to `config/locales.php` (`'fr' => ['name'=>'Français','dir'=>'ltr']`).
2. Copy `lang/en` → `lang/fr` and translate the files (+ `fr.json` if used).
3. Done. The middleware, switcher, direction handling, validation, and API all pick it up automatically. (Add the RTL flag in the config row if the new language is RTL — CSS already handles both via logical properties.)

---

## Rollout order (incremental, low-risk)
1. **Infra:** `config/locales.php`, `lang:publish`, `SetLocale` middleware, register it, `locale` columns + profile/switcher plumbing. (No visible change yet; default stays Arabic.)
2. **API errors/messages:** refactor `ApiResponse` + exceptions to `code` + `__()`, fill `errors.php` for ar/en/tr. App immediately benefits.
3. **Owner portal**, page by page, into `portal.php` + the switcher + RTL/LTR CSS pass.
4. **Admin portal.**
5. **(Optional) user-generated content** translation as a separate phase.

## Testing
- Feature test: same endpoint with `X-Locale: en` vs `tr` vs `ar` returns the right `message` and identical `code`.
- Validation messages localize per `Accept-Language`.
- Middleware rejects/falls back on an unsupported locale.
- Portal renders `dir=rtl` for ar and `dir=ltr` for en/tr; the switcher persists the choice (cookie + DB).
- Missing-key guard: in non-production, enable a fallback log so any untranslated key surfaces during QA.

## Recommendation on packages
Stick to **Laravel's built-in** localization (`__()`, `lang/`, `App::setLocale`) — it's the optimal, dependency-free choice and exactly fits this need. Only reach for `spatie/laravel-translatable` later, and only for **translating user-generated DB content** (Phase 5), not for UI strings.
