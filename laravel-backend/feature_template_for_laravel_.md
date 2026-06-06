# Laravel Feature Template — anis Backend Coding Standard

> Purpose: every feature in this backend is built the **same way**, with the same layers, naming, and quality bar — mirroring the discipline of the Flutter `feature_template.md` (Entities / Models / Repositories / Use Cases). Follow this document exactly when adding any feature. No shortcuts, no logic in controllers, no untested money/time math.

---

## 0. Golden rules (read first)

1. **Thin controllers, fat actions.** Controllers only: receive a validated request → call one Action/Service → return a Resource. Zero business logic in controllers.
2. **Every input is validated by a Form Request.** Never read `$request->input()` raw in a controller.
3. **Every output goes through an API Resource.** Never return a raw model or `->toArray()`.
4. **One business action = one class** (Action or Use Case). This is the Laravel equivalent of a Flutter Use Case.
5. **Repositories wrap Eloquent.** Controllers/Actions depend on a repository **interface**, never on a model query directly.
6. **Money and time math runs inside a DB transaction** and is **unit-tested**.
7. **Strict types everywhere:** `declare(strict_types=1);` at the top of every PHP file. Typed properties, typed params, typed returns.
8. **Constants/Enums over magic strings.** Use PHP `enum` for status/type fields.
9. **No N+1 queries.** Always eager-load (`with()`); the test suite asserts query counts on list endpoints. Enable `Model::preventLazyLoading()` in non-production so violations fail loudly.
10. **Consistent response envelope:** `{ "success": bool, "data": ..., "message": string }` (+ `meta` for paginated lists).
11. **All list endpoints are paginated.** Never return an unbounded `->get()`; default 20/page, hard cap 100.
12. **Every action is authorized by a Policy.** Controllers call `$this->authorize(...)`; Form Request `authorize()` stays `true` and authorization lives in the Policy layer.
13. **Audit every balance/state change.** Money/time mutations write an immutable audit row and a structured log line.

---

## 1. Per-feature folder layout

Each feature is a vertical slice. Example for a feature called `Session`:

```
app/
  Domain/
    Session/
      Actions/
        CreateSessionAction.php
        JoinSessionAction.php
        LeaveSessionAction.php
      Data/
        CreateSessionData.php          # DTO (typed input object)
      Enums/
        SessionType.php
        SessionStatus.php
      Contracts/
        SessionRepositoryInterface.php # the "Repository" abstraction
      Repositories/
        EloquentSessionRepository.php  # concrete impl
      Models/
        StudySession.php
      Exceptions/
        SessionFullException.php
  Http/
    Controllers/Api/V1/
      SessionController.php
    Requests/Session/
      CreateSessionRequest.php
    Resources/Session/
      SessionResource.php
      SessionParticipantResource.php
database/
  migrations/...
  factories/StudySessionFactory.php
  seeders/SessionSeeder.php
tests/
  Feature/Session/SessionApiTest.php
  Unit/Session/JoinSessionActionTest.php
routes/
  api.php   (or routes/api/session.php included from api.php)
```

> The `app/Domain/<Feature>/` grouping is the Laravel analogue of a Flutter feature folder. Keep everything for one feature together so it reads like a self-contained module.

---

## 2. Layer-by-layer rules (with the Flutter mapping)

| Flutter layer | Laravel equivalent | Rule |
|---|---|---|
| Entity | Eloquent **Model** | Casts, relationships, scopes only. No HTTP, no formatting. |
| Model (JSON) | API **Resource** | Shapes the JSON response. The single source of truth for output. |
| Repository (abstract) | **Interface** in `Contracts/` | Defines data-access methods. Bound to a concrete impl in a ServiceProvider. |
| Repository (impl) | **EloquentRepository** | The only place Eloquent queries for this feature live. |
| Use Case | **Action** class | One public method: `handle()` / `__invoke()`. One responsibility. |
| Params object | **DTO** (`Data/`) | Typed object built from the validated request. No arrays passed around. |

### 2.1 Model
```php
<?php
declare(strict_types=1);

namespace App\Domain\Session\Models;

use App\Domain\Session\Enums\SessionStatus;
use App\Domain\Session\Enums\SessionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class StudySession extends Model
{
    use HasUuids;

    protected $table = 'study_sessions';
    protected $guarded = ['id'];

    protected $casts = [
        'type'       => SessionType::class,
        'status'     => SessionStatus::class,
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'max_seats'  => 'integer',
    ];

    public function workspace()    { return $this->belongsTo(\App\Domain\Workspace\Models\Workspace::class); }
    public function host()         { return $this->belongsTo(\App\Models\User::class, 'host_id'); }
    public function participants() {
        return $this->belongsToMany(\App\Models\User::class, 'session_participants', 'session_id', 'user_id')
                    ->withPivot('joined_at');
    }

    public function scopeUpcoming($q) { return $q->where('status', SessionStatus::UPCOMING); }
}
```

### 2.2 Enum (no magic strings)
```php
<?php
declare(strict_types=1);

namespace App\Domain\Session\Enums;

enum SessionStatus: string
{
    case UPCOMING  = 'UPCOMING';
    case ONGOING   = 'ONGOING';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
}
```

### 2.3 Repository contract + impl
```php
<?php
declare(strict_types=1);

namespace App\Domain\Session\Contracts;

use App\Domain\Session\Models\StudySession;
use Illuminate\Support\Collection;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SessionRepositoryInterface
{
    // Paginated + caller's id so is_joined is computed in SQL (no N+1).
    public function paginateByWorkspace(?string $workspaceId, ?string $forUserId, int $perPage): LengthAwarePaginator;
    public function findOrFail(string $id): StudySession;
    public function lockForUpdate(string $id): StudySession;   // row-locked fetch for capacity checks
    public function create(array $attributes): StudySession;
    public function addParticipant(StudySession $session, string $userId): void;
    public function removeParticipant(StudySession $session, string $userId): void;
}
```
```php
final class EloquentSessionRepository implements SessionRepositoryInterface
{
    public function paginateByWorkspace(?string $workspaceId, ?string $forUserId, int $perPage): LengthAwarePaginator
    {
        return StudySession::query()
            ->with(['host', 'workspace'])          // eager-loaded -> no N+1
            ->withCount('participants')
            // is_joined resolved in one subquery, not by loading every participant row:
            ->when($forUserId, fn ($q) => $q->withExists([
                'participants as is_joined' => fn ($p) => $p->where('users.id', $forUserId),
            ]))
            ->when($workspaceId, fn ($q) => $q->where('workspace_id', $workspaceId))
            ->latest('start_time')
            ->paginate($perPage);                  // pagination is mandatory on list endpoints
    }

    public function lockForUpdate(string $id): StudySession
    {
        return StudySession::query()->whereKey($id)->lockForUpdate()->firstOrFail();
    }
    // findOrFail / create / addParticipant / removeParticipant ...
}
```
Bind it in a service provider:
```php
$this->app->bind(SessionRepositoryInterface::class, EloquentSessionRepository::class);
```

### 2.4 DTO (typed input)
```php
<?php
declare(strict_types=1);

namespace App\Domain\Session\Data;

final readonly class CreateSessionData
{
    public function __construct(
        public string $workspaceId,
        public string $hostId,
        public string $title,
        public ?string $description,
        public string $type,
        public \DateTimeImmutable $startTime,
        public \DateTimeImmutable $endTime,
        public ?int $maxSeats,
    ) {}

    public static function fromRequest(CreateSessionRequest $r, string $hostId): self
    {
        return new self(
            workspaceId: $r->string('workspace_id'),
            hostId:      $hostId,
            title:       $r->string('title'),
            description: $r->input('description'),
            type:        $r->string('type'),
            startTime:   new \DateTimeImmutable($r->string('start_time')),
            endTime:     new \DateTimeImmutable($r->string('end_time')),
            maxSeats:    $r->integer('max_seats') ?: null,
        );
    }
}
```

### 2.5 Action (the Use Case)
```php
<?php
declare(strict_types=1);

namespace App\Domain\Session\Actions;

use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Exceptions\AlreadyJoinedException;
use App\Domain\Session\Exceptions\SessionFullException;
use App\Domain\Session\Models\StudySession;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class JoinSessionAction
{
    public function __construct(private SessionRepositoryInterface $sessions) {}

    public function handle(string $sessionId, string $userId): StudySession
    {
        return DB::transaction(function () use ($sessionId, $userId) {
            // Lock the session row so two concurrent joins can't both pass the seat check.
            $session = $this->sessions->lockForUpdate($sessionId);

            if ($session->max_seats !== null
                && $session->participants()->count() >= $session->max_seats) {
                throw new SessionFullException();
            }

            try {
                $this->sessions->addParticipant($session, $userId);
            } catch (QueryException $e) {
                // Unique index (session_id,user_id) violated -> clean 409, never a 500.
                if ($this->isUniqueViolation($e)) {
                    throw new AlreadyJoinedException();
                }
                throw $e;
            }

            Log::info('session.joined', ['session_id' => $sessionId, 'user_id' => $userId]);

            return $session->fresh(['host', 'workspace'])->loadCount('participants');
        });
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062   // MySQL duplicate entry
            || ($e->getCode() === '23000');
    }
}
```

### 2.6 Form Request (validation only)
```php
<?php
declare(strict_types=1);

namespace App\Http\Requests\Session;

use Illuminate\Foundation\Http\FormRequest;

final class CreateSessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }   // policy handled separately

    public function rules(): array
    {
        return [
            'workspace_id' => ['required', 'uuid', 'exists:workspaces,id'],
            'title'        => ['required', 'string', 'max:120'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'type'         => ['required', 'in:STUDY_GROUP,EVENT,FOCUS_BLOCK'],
            'start_time'   => ['required', 'date', 'after:now'],
            'end_time'     => ['required', 'date', 'after:start_time'],
            'max_seats'    => ['nullable', 'integer', 'min:1', 'max:500'],
        ];
    }
}
```

### 2.7 Resource (output shaping)
```php
<?php
declare(strict_types=1);

namespace App\Http\Resources\Session;

use Illuminate\Http\Resources\Json\JsonResource;

final class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'workspace_id'       => $this->workspace_id,
            'host_id'            => $this->host_id,
            'title'              => $this->title,
            'type'               => $this->type->value,
            'status'             => $this->status->value,
            'start_time'         => $this->start_time->toIso8601String(),
            'end_time'           => $this->end_time->toIso8601String(),
            'max_seats'          => $this->max_seats,
            'participants_count' => $this->whenCounted('participants'),
            // Reads the `is_joined` column added by withExists() — no participant rows loaded.
            'is_joined'          => $request->user() !== null
                ? (bool) ($this->is_joined ?? false)
                : false,
        ];
    }
}
```

### 2.8 Controller (thin)
```php
<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Session\Actions\CreateSessionAction;
use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Data\CreateSessionData;
use App\Domain\Session\Models\StudySession;
use App\Http\Controllers\Controller;
use App\Http\Requests\Session\CreateSessionRequest;
use App\Http\Resources\Session\SessionResource;
use App\Support\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

final class SessionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, SessionRepositoryInterface $sessions)
    {
        $this->authorize('viewAny', StudySession::class);   // policy gate

        $perPage = min((int) $request->integer('per_page', 20), 100);   // capped page size
        $page = $sessions->paginateByWorkspace(
            $request->string('workspace_id')->value() ?: null,
            $request->user()?->id,
            $perPage,
        );

        // ApiResponse::paginated() keeps the {success,data,message} envelope AND meta.
        return ApiResponse::paginated(SessionResource::collection($page));
    }

    public function store(CreateSessionRequest $request, CreateSessionAction $action)
    {
        $this->authorize('create', StudySession::class);   // policy gate

        $session = $action->handle(
            CreateSessionData::fromRequest($request, $request->user()->id)
        );
        return ApiResponse::created(new SessionResource($session));
    }
}
```

---

## 3. Standard response envelope

A single helper keeps every endpoint consistent (`app/Support/ApiResponse.php`):
```php
<?php
declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ApiResponse
{
    public static function ok($data, string $message = 'OK'): JsonResponse
        { return response()->json(['success' => true, 'data' => $data, 'message' => $message]); }

    public static function created($data, string $message = 'Created'): JsonResponse
        { return response()->json(['success' => true, 'data' => $data, 'message' => $message], 201); }

    public static function error(string $message, int $code = 400, $data = null): JsonResponse
        { return response()->json(['success' => false, 'data' => $data, 'message' => $message], $code); }

    // Standard envelope for paginated list endpoints: data = rows, meta = paging info.
    public static function paginated(ResourceCollection $collection, string $message = 'OK'): JsonResponse
    {
        $paginator = $collection->resource;   // the underlying LengthAwarePaginator
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $collection->collection,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}
```
Domain exceptions render through `App\Exceptions\Handler` into this same shape (e.g. `SessionFullException` → 409, `NoActiveSubscriptionException` → 402).

---

## 3.1 Security standard

Security is not a separate "later" task. Every feature must define who can call
it, what data can leave the API, and how abuse is slowed down.

### Route protection

- All API routes live under `/api/v1`.
- Routes are protected with `auth:sanctum` unless the route is explicitly marked
  as public in the feature notes.
- Guest-readable routes are allowed only for safe read-only data that the mobile
  app shows before login, such as workspace lists/details, public plans, and
  public session previews.
- Public auth routes (`/auth/register`, `/auth/login`) must use rate limiting.
- Public guest-readable routes must use `throttle:public`, pagination, strict
  query validation, and redacted API Resources. They must not expose user phone
  numbers, WhatsApp numbers, paid balances, private attendance, or membership
  state.
- Mutating routes also use Policies, even when they already have
  `auth:sanctum`.

```php
Route::prefix('v1')->group(function () {
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

    // Public, read-only catalog data for guest browsing in the mobile app.
    Route::middleware('throttle:public')->group(function () {
        Route::get('/workspaces', [WorkspaceController::class, 'index']);
        Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show']);
        Route::get('/plans', [PlanController::class, 'index']);
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::get('/sessions', [SessionController::class, 'index']);
        Route::post('/sessions', [SessionController::class, 'store']);
        Route::post('/sessions/{session}/join', [SessionController::class, 'join']);
        Route::post('/visits/check-in', [VisitController::class, 'checkIn']);
        Route::post('/visits/{visit}/check-out', [VisitController::class, 'checkOut']);
    });
});
```

Configure named rate limiters in `AppServiceProvider` or a dedicated route
service provider:

```php
RateLimiter::for('auth', fn (Request $request) => Limit::perMinute(5)->by(
    $request->ip().'|'.$request->input('phone_number')
));

RateLimiter::for('public', fn (Request $request) => Limit::perMinute(60)->by(
    $request->ip().'|'.substr((string) $request->userAgent(), 0, 80)
));

RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)->by(
    $request->user()?->id ?: $request->ip()
));
```

### Guest-readable routes without tokens

The Anis mobile app supports a guest mode. Guest mode is local-only on the
client: it lets users browse safe catalog data, while `ActionGuard` sends guests
to login before protected actions such as joining a session, creating a session,
QR check-in, checkout, viewing private profile data, or touching subscription
balance.

Do **not** create fake guest tokens just to read public data. Use public,
read-only endpoints with strict controls:

- Only `GET` endpoints can be guest-readable.
- Every guest-readable endpoint must be explicitly listed in the route file and
  documented as `public`.
- Use a separate Resource or conditional Resource fields for public responses.
- Public workspace/session responses must hide member contact fields
  (`phone_number`, `whatsapp_number`) unless the user is authenticated and the
  feature intentionally allows contact discovery.
- Public endpoints must be paginated, cache-friendly, and filter-limited.
- Validate every query parameter with a Form Request, including `lat`, `lng`,
  `workspace_id`, `per_page`, search, and filters.
- Never return user-specific flags from public endpoints unless the request has
  a real authenticated user. For guests, values such as `is_joined`,
  `can_check_in`, and `remaining_minutes` must be absent or safely false/null.
- Public endpoints use stricter throttling than authenticated API routes.
- Public endpoints should log aggregate abuse signals, not PII.

Example Resource rule:

```php
return [
    'id'          => $this->id,
    'name'        => $this->name,
    'address'     => $this->address,
    'capacity'    => $this->capacity,
    'distance_km' => $this->when(isset($this->distance_km), $this->distance_km),
    // Only authenticated session detail responses may expose contact data.
    'host_phone_number' => $this->when($request->user() !== null, $this->host?->phone_number),
];
```

Optional authenticated context is allowed for a public route only when the route
still works safely without a user. If no valid Sanctum user is present, the
endpoint behaves as guest and returns the redacted public shape.

### Passwords and tokens

- Passwords are always hashed with `Hash::make()`. Never store, return, or log
  raw passwords.
- Registration validates `password` with `required`, `confirmed`, `min:8`,
  mixed case, and numbers. Prefer Laravel's `Password` rule.
- Login verifies with `Hash::check()`.
- Sanctum tokens are returned only on register/login, stored by the client, and
  never logged in full.
- Logout revokes the current token with `$request->user()->currentAccessToken()->delete()`.

```php
'password' => [
    'required',
    'confirmed',
    Password::min(8)->mixedCase()->numbers(),
],
```

### Authorization and ownership

- Authentication answers "who are you?". Policy authorization answers "may you
  do this?". Both are required for protected business routes.
- Every `view`, `update`, `delete`, `join`, `leave`, `checkIn`, and `checkOut`
  operation must prove the current user owns the resource or is allowed by role.
- Feature tests must include cross-user denial cases: user A cannot view,
  update, delete, check out, or mutate user B's resources.
- Policies should fail closed. If the template does not explicitly allow an
  action, return `false`.

### Sensitive output

- API Resources are the only place response fields are chosen.
- Never expose `password`, `remember_token`, raw Sanctum tokens, internal audit
  notes, ledger internals, or private operational fields.
- Models containing secrets define `$hidden`; Resources still explicitly choose
  output fields.
- Do not return full related models just because they were eager-loaded.

### Validation and mass assignment

- Form Requests validate all external input. Actions and repositories receive
  DTOs or typed values, not raw request arrays.
- Never pass `$request->all()` into `create()` or `update()`.
- Use `$fillable` for user/auth models. If `$guarded = ['id']` is used for a
  simple domain model, the repository must still pass a whitelisted attributes
  array.
- Validation failures, auth failures, and policy failures are rendered through
  the same response envelope.

### Production safety

- `APP_DEBUG=false` in production.
- Production traffic must use HTTPS. Never generate public API URLs with plain
  HTTP in production config.
- CORS allows only the mobile/web origins that actually need access.
- Logs may include IDs and event names, never passwords, full tokens, or PII
  beyond what support truly needs.

---

## 4. Naming conventions

| Thing | Convention | Example |
|---|---|---|
| Table | snake_case plural | `workspace_visits` |
| Model | StudlyCase singular, `final` | `WorkspaceVisit` |
| Migration | timestamped, descriptive | `..._create_workspace_visits_table` |
| Controller | `<Feature>Controller`, `Api\V1` namespace | `VisitController` |
| Action | verb + noun + `Action` | `CheckOutVisitAction` |
| Form Request | verb + noun + `Request` | `CheckInRequest` |
| Resource | noun + `Resource` | `VisitResource` |
| Interface | noun + `Interface` | `VisitRepositoryInterface` |
| Enum | singular noun | `VisitStatus` |
| JSON keys | snake_case (match Flutter models) | `check_in_at` |

---

## 5. Quality gates (must pass before a feature is "done")

Run all of these; CI enforces them:

1. **Pint (code style):** `vendor/bin/pint` — Laravel preset, zero diffs.
2. **PHPStan / Larastan level 8:** `vendor/bin/phpstan analyse` — no errors.
3. **Tests green + meaningful coverage:** `php artisan test`.
   - One **Feature test** per endpoint (happy path + each error path: 401/402/403/404/409/422).
   - One **Unit test** per Action containing money/time logic.
   - Assert the JSON envelope shape and HTTP status explicitly.
4. **No N+1:** list endpoints assert a fixed query count (use `DB::enableQueryLog()` or `assertDatabaseQueryCount`).
5. **Migrations are reversible:** `migrate:fresh` and `migrate:rollback` both run clean.
6. **Factory + Seeder exist** for the feature's models so tests and local demo data are trivial.

### Example feature test
```php
public function test_user_can_join_session(): void
{
    $user    = User::factory()->withActiveSubscription()->create();
    $session = StudySession::factory()->create(['max_seats' => 2]);

    $this->actingAs($user)
        ->postJson("/api/v1/sessions/{$session->id}/join")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.is_joined', true);

    $this->assertDatabaseHas('session_participants', [
        'session_id' => $session->id, 'user_id' => $user->id,
    ]);
}

public function test_join_fails_when_session_full(): void
{
    $session = StudySession::factory()->create(['max_seats' => 1]);
    SessionParticipant::factory()->for($session, 'session')->create();
    $user = User::factory()->withActiveSubscription()->create();

    $this->actingAs($user)
        ->postJson("/api/v1/sessions/{$session->id}/join")
        ->assertStatus(409)
        ->assertJsonPath('success', false);
}
```

---

## 6. Critical-path rules (subscription & QR time math)

These touch users' paid balance, so they get extra discipline:

1. **Always inside `DB::transaction()`** with row locking (`lockForUpdate()`) on the subscription row to prevent double-spend on concurrent check-outs.
2. **Compute duration server-side** from `check_in_at`/`now()` — never trust a client-sent duration.
3. **Guard before mutate:** check-in throws `NoActiveSubscriptionException` (402) if no `ACTIVE` subscription, zero `remaining_minutes`, or past `expires_at`. A user may hold only **one** open (`CHECKED_IN`) visit — enforce with a partial unique index or a guard query.
4. **Idempotency:** checking out an already-checked-out visit returns the existing result, not a second deduction.
5. **Unit-test the math:** 90 min visit → `remaining_minutes` drops by exactly 90; clamp at 0, never negative.

```php
final readonly class CheckOutVisitAction
{
    public function handle(string $visitId, string $userId): WorkspaceVisit
    {
        return DB::transaction(function () use ($visitId, $userId) {
            $visit = WorkspaceVisit::where('id', $visitId)->where('user_id', $userId)
                        ->lockForUpdate()->firstOrFail();

            if ($visit->status === VisitStatus::CHECKED_OUT) {
                return $visit;                       // idempotent
            }

            $minutes = (int) ceil($visit->check_in_at->diffInMinutes(now()));

            $visit->update([
                'status'           => VisitStatus::CHECKED_OUT,
                'check_out_at'     => now(),
                'duration_minutes' => $minutes,
            ]);

            if ($sub = $visit->subscription()->lockForUpdate()->first()) {
                if ($sub->remaining_minutes !== null) {
                    $before = $sub->remaining_minutes;
                    $after  = max(0, $before - $minutes);
                    $sub->update(['remaining_minutes' => $after]);

                    // Immutable audit trail — answers "why were my minutes deducted?"
                    SubscriptionLedger::create([
                        'subscription_id' => $sub->id,
                        'visit_id'        => $visit->id,
                        'user_id'         => $userId,
                        'change_minutes'  => -($before - $after),
                        'balance_after'   => $after,
                        'reason'          => 'WORKSPACE_VISIT',
                    ]);
                }
            }

            Log::info('visit.checked_out', [
                'visit_id' => $visit->id, 'user_id' => $userId, 'minutes' => $minutes,
            ]);

            return $visit->fresh();
        });
    }
}
```

---

## 6.1 Authorization (Policies)

Validation (Form Request) answers *"is the input well-formed?"*. Authorization (Policy) answers *"is this user allowed?"*. They are separate layers — never mix them.

- One Policy per model: `php artisan make:policy SessionPolicy --model=StudySession`.
- Form Request `authorize()` returns `true`; the real check is `$this->authorize(...)` in the controller (or a route `can:` middleware).
- Policy methods return `bool`; denial auto-renders 403 through the exception handler into the standard envelope.

```php
<?php
declare(strict_types=1);

namespace App\Policies;

use App\Domain\Session\Models\StudySession;
use App\Models\User;

final class SessionPolicy
{
    public function viewAny(?User $user): bool { return true; }              // public list
    public function view(?User $user, StudySession $s): bool { return true; }
    public function create(User $user): bool
    {
        return $user->activeSubscription()->exists();                       // only subscribed users host
    }
    public function delete(User $user, StudySession $s): bool
    {
        return $s->host_id === $user->id;                                   // only the host
    }
}
```
Register in `AuthServiceProvider::$policies` (or rely on auto-discovery), and gate every controller method.

---

## 6.2 Observability & audit trail

Two distinct concerns — keep both:

**Structured logs** (operational, transient): every Action logs a single structured line on success and on domain failure (`Log::info('session.joined', [...])`, `Log::warning('visit.checkout_denied', [...])`). Use event names as `noun.verb`. Never log secrets, passwords, or full tokens. Wire a request-id into the log context so a request can be traced end-to-end.

**Audit ledger** (financial, permanent): every change to a paid balance writes an immutable row to `subscription_ledgers` — append-only, never updated or deleted. This is what lets support answer a billing dispute and what an auditor reads.

```php
// migration: ..._create_subscription_ledgers_table.php
Schema::create('subscription_ledgers', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->foreignUuid('subscription_id')->constrained()->cascadeOnDelete();
    $t->foreignUuid('visit_id')->nullable()->constrained('workspace_visits');
    $t->foreignUuid('user_id')->constrained();
    $t->integer('change_minutes');        // negative = deduction, positive = top-up
    $t->integer('balance_after');         // running balance snapshot
    $t->string('reason');                 // WORKSPACE_VISIT | TOP_UP | ADJUSTMENT | REFUND
    $t->timestamp('created_at')->useCurrent();   // no updated_at — rows are immutable
    $t->index(['subscription_id', 'created_at']);
});
```
Rule: the ledger write happens **inside the same transaction** as the balance update (see `CheckOutVisitAction`), so a deduction and its audit row are atomic — you can never have one without the other.

---

## 7. New-feature checklist (copy into each PR)

```
[ ] Migration written, reversible, indexed; factory + seeder added
[ ] Model: final, strict_types, casts, enums, relationships, scopes
[ ] Enums for all status/type fields (no magic strings)
[ ] Repository interface + Eloquent impl; bound in a ServiceProvider
[ ] DTO for every write action (no arrays passed around)
[ ] Action class per business operation; transactions on money/time
[ ] Form Request validating every input (incl. all error cases)
[ ] API Resource shaping every response (snake_case keys)
[ ] Controller is thin (request -> action -> resource only)
[ ] List endpoints paginated (default 20, cap 100); is_joined-style flags via subquery, not loaded rows
[ ] Security: protected routes use auth:sanctum; public routes are explicitly marked
[ ] Guest-readable routes are GET-only, throttled, paginated, validated, and redacted
[ ] Auth endpoints rate-limited; API endpoints throttled
[ ] Passwords hashed with Hash::make; login uses Hash::check; tokens never logged
[ ] Resources hide sensitive fields; no raw models returned
[ ] Policy created + every controller method calls $this->authorize(...)
[ ] Ownership tests prove user A cannot access or mutate user B resources
[ ] Concurrency: row-locked (lockForUpdate) on seat/balance checks; unique-violation -> clean 409
[ ] Audit: balance/state changes write an immutable ledger row in the same transaction + structured log
[ ] Routes under /api/v1, protected with auth:sanctum + policies
[ ] Domain exceptions mapped to correct HTTP codes in the Handler
[ ] Feature tests: happy path + 401/402/403/404/409/422
[ ] Unit tests for any balance/time calculation (and the ledger row it writes)
[ ] Pint clean, PHPStan/Larastan level 8 clean, all tests green
[ ] No N+1 (eager-loaded; query-count asserted on lists; preventLazyLoading on in dev)
```

---

### TL;DR
Controllers stay dumb and **authorize via Policies**. Business logic lives in **Actions**. Data access lives behind a **Repository interface** and list endpoints are always **paginated**. Inputs are **Form Requests + DTOs**, outputs are **Resources**, statuses are **Enums**. Money/time runs in **row-locked transactions**, is **unit-tested**, and writes an **immutable audit ledger** alongside a structured log. Concurrency-sensitive checks (seats, balance) lock the row; unique-constraint races return a clean **409**, never a 500. If a PR can't tick the section 7 checklist, it isn't done.
