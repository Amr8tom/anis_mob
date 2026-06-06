# anis — Backend Design & Implementation Guide (Laravel)

**Stack:** Laravel 11 + Eloquent ORM + MySQL + Laravel Sanctum (token auth)
**Frontend:** Flutter, Clean Architecture (Entities / Models / Repositories / Use Cases)

> Scope guardrails (by design): shared coworking only — **no private/manager offices**, **no in-app chat** (contact via displayed phone/WhatsApp), **no OTP** (phone + password auth). One unified subscription grants access to **any** workspace in the network.

---

## Step 1 — Database Schema & Relationships

### 1.1 Relationship map

```
User ──1:M──> Subscription        (subscription history; one ACTIVE at a time)
User ──1:M──> Session             (sessions a user hosts/created)
User ──M:N──> Session             (sessions a user joined)        via session_participants
User ──1:M──> WorkspaceVisit      (check-in / check-out records)
Workspace ──1:M──> Session        (sessions happen inside a workspace)
Workspace ──1:M──> WorkspaceVisit (visits / attendance)
Plan ──1:M──> Subscription        (a plan instantiated per user)
```

- One **Subscription** is network-wide, not tied to a single workspace (core product rule).
- **Session** belongs to one Workspace + one host User; users join via the `session_participants` pivot (M:N).
- **WorkspaceVisit** = one QR check-in→check-out cycle; deducts studied minutes from the active subscription.

### 1.2 Migrations (`database/migrations/`)

```php
// 0001_create_users_table.php
Schema::create('users', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->string('full_name');
    $t->string('phone_number')->unique();      // login identifier
    $t->string('whatsapp_number');             // shown for out-of-app contact
    $t->string('password');                    // no OTP -> hashed password
    $t->string('avatar_url')->nullable();
    $t->timestamps();
});

// 0002_create_plans_table.php
Schema::create('plans', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->string('name');                        // "Monthly", "20 Hours"
    $t->text('description')->nullable();
    $t->integer('price_cents');
    $t->string('currency')->default('EGP');
    $t->integer('included_minutes')->nullable();   // time-balance plans
    $t->integer('duration_days')->nullable();      // date-bound plans
    $t->boolean('is_active')->default(true);
    $t->timestamps();
});

// 0003_create_subscriptions_table.php
Schema::create('subscriptions', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $t->foreignUuid('plan_id')->constrained();
    $t->enum('status', ['ACTIVE','EXPIRED','CANCELLED'])->default('ACTIVE');
    $t->timestamp('started_at')->useCurrent();
    $t->timestamp('expires_at')->nullable();
    $t->integer('remaining_minutes')->nullable();  // decremented on check-out
    $t->timestamps();
    $t->index(['user_id','status']);
});

// 0004_create_workspaces_table.php
Schema::create('workspaces', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->string('name');
    $t->text('description')->nullable();
    $t->string('address');
    $t->double('latitude')->nullable();
    $t->double('longitude')->nullable();
    $t->string('cover_image_url')->nullable();
    $t->integer('capacity')->nullable();
    $t->string('open_time')->nullable();   // "08:00"
    $t->string('close_time')->nullable();  // "23:00"
    $t->integer('day_calculation_hours')->default(8); // one subscription day after X+ hours
    $t->boolean('is_active')->default(true);
    $t->timestamps();
});

// 0005_create_sessions_table.php  (use a non-reserved name; table = "study_sessions")
Schema::create('study_sessions', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
    $t->foreignUuid('host_id')->constrained('users');
    $t->string('title');
    $t->text('description')->nullable();
    $t->enum('type', ['STUDY_GROUP','EVENT','FOCUS_BLOCK'])->default('STUDY_GROUP');
    $t->enum('status', ['UPCOMING','ONGOING','COMPLETED','CANCELLED'])->default('UPCOMING');
    $t->timestamp('start_time');
    $t->timestamp('end_time');
    $t->integer('max_seats')->nullable();
    $t->timestamps();
    $t->index(['workspace_id','status']);
});

// 0006_create_session_participants_table.php
Schema::create('session_participants', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->foreignUuid('session_id')->constrained('study_sessions')->cascadeOnDelete();
    $t->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $t->timestamp('joined_at')->useCurrent();
    $t->unique(['session_id','user_id']);   // join once
});

// 0007_create_workspace_visits_table.php
Schema::create('workspace_visits', function (Blueprint $t) {
    $t->uuid('id')->primary();
    $t->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $t->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
    $t->foreignUuid('subscription_id')->nullable()->constrained();
    $t->enum('status', ['CHECKED_IN','CHECKED_OUT'])->default('CHECKED_IN');
    $t->timestamp('check_in_at')->useCurrent();
    $t->timestamp('check_out_at')->nullable();
    $t->integer('duration_minutes')->nullable();
    $t->timestamps();
    $t->index(['user_id','status']);
});
```

### 1.3 Eloquent models (`app/Models/`)

```php
// User.php
class User extends Authenticatable {
    use HasApiTokens, HasUuids;
    protected $fillable = ['full_name','phone_number','whatsapp_number','password','avatar_url'];
    protected $hidden   = ['password'];

    public function subscriptions()  { return $this->hasMany(Subscription::class); }
    public function hostedSessions() { return $this->hasMany(StudySession::class, 'host_id'); }
    public function joinedSessions() {
        return $this->belongsToMany(StudySession::class, 'session_participants', 'user_id', 'session_id')
                    ->withPivot('joined_at');
    }
    public function visits()         { return $this->hasMany(WorkspaceVisit::class); }
    public function activeSubscription() {
        return $this->hasOne(Subscription::class)->where('status','ACTIVE')->latestOfMany();
    }
}

// Workspace.php
class Workspace extends Model {
    use HasUuids;
    protected $guarded = [];
    public function sessions() { return $this->hasMany(StudySession::class); }
    public function visits()   { return $this->hasMany(WorkspaceVisit::class); }
}

// StudySession.php
class StudySession extends Model {
    use HasUuids;
    protected $table = 'study_sessions';
    protected $guarded = [];
    protected $casts = ['start_time'=>'datetime','end_time'=>'datetime'];
    public function workspace()    { return $this->belongsTo(Workspace::class); }
    public function host()         { return $this->belongsTo(User::class, 'host_id'); }
    public function participants() {
        return $this->belongsToMany(User::class, 'session_participants', 'session_id', 'user_id')
                    ->withPivot('joined_at');
    }
}

// Subscription.php
class Subscription extends Model {
    use HasUuids;
    protected $guarded = [];
    protected $casts = ['started_at'=>'datetime','expires_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function plan() { return $this->belongsTo(Plan::class); }
}

// Plan.php
class Plan extends Model {
    use HasUuids;
    protected $guarded = [];
    public function subscriptions() { return $this->hasMany(Subscription::class); }
}

// WorkspaceVisit.php
class WorkspaceVisit extends Model {
    use HasUuids;
    protected $guarded = [];
    protected $casts = ['check_in_at'=>'datetime','check_out_at'=>'datetime'];
    public function user()      { return $this->belongsTo(User::class); }
    public function workspace() { return $this->belongsTo(Workspace::class); }
}
```

---

## Step 2 — Clean Architecture Mapping (Flutter / Dart)

Identical to the Node version — the API JSON contract is the same, only the backend implementation differs. For each object: **Entity** (pure Dart), **Model** (`fromJson`/`toJson`), **Repository** interface, **Use Cases**.

### Entities
`UserEntity, WorkspaceEntity, SessionEntity, SubscriptionEntity, PlanEntity, WorkspaceVisitEntity`
(same fields as the Node doc — Session carries computed `participantsCount` and `isJoined`).

### Models (data layer)
One `*Model extends *Entity` per entity, with `fromJson` mapping Laravel's snake_case keys. Example:

```dart
factory SessionModel.fromJson(Map<String, dynamic> json) => SessionModel(
  id: json['id'],
  workspaceId: json['workspace_id'],
  hostId: json['host_id'],
  title: json['title'],
  type: json['type'],
  status: json['status'],
  startTime: DateTime.parse(json['start_time']),
  endTime: DateTime.parse(json['end_time']),
  maxSeats: json['max_seats'],
  participantsCount: json['participants_count'] ?? 0,
  isJoined: json['is_joined'] ?? false,
);
```
> Tip: keep keys snake_case end-to-end, OR configure Laravel API Resources to camelCase — pick one and stay consistent so your Dart models stay simple.

### Repository interfaces
`AuthRepository, WorkspaceRepository, SessionRepository, SubscriptionRepository, AttendanceRepository`
(same method signatures as the Node doc).

### Use Cases
```
Auth        : RegisterUseCase, LoginUseCase, GetCurrentUserUseCase, LogoutUseCase
Workspaces  : GetWorkspacesUseCase, GetWorkspaceByIdUseCase
Sessions    : GetSessionsUseCase, GetSessionByIdUseCase, CreateSessionUseCase,
              JoinSessionUseCase, LeaveSessionUseCase
Subscription: GetActiveSubscriptionUseCase, GetPlansUseCase
Attendance  : CheckInUseCase, CheckOutUseCase, GetOpenVisitUseCase
```

---

## Step 3 — Remote Data Sources (REST API contracts)

Base URL: `/api/v1`. Auth: Sanctum bearer token — `Authorization: Bearer <token>`. Response envelope: `{ "success": bool, "data": ..., "message": string }`.

### Auth (Sanctum)
```
POST /auth/register   Body: { full_name, phone_number, whatsapp_number, password }
                      201 : { data: { user, token } }
POST /auth/login      Body: { phone_number, password }
                      200 : { data: { user, token } }
GET  /auth/me         (auth)  200 : { data: { user } }
POST /auth/logout     (auth)  200 : { success: true }   // revokes current token
```

### Workspaces
```
GET  /workspaces?lat=&lng=    (auth)  200 : { data: [ { id,name,address,latitude,longitude,cover_image_url,capacity,day_calculation_hours,distance_km } ] }
GET  /workspaces/{id}         (auth)  200 : { data: { workspace, active_sessions_count } }
```

### Sessions
```
GET    /sessions?workspace_id=   (auth)  200 : { data: [ session... ] }
GET    /sessions/{id}            (auth)  200 : { data: { session, participants:[ {id,full_name,phone_number,whatsapp_number,avatar_url} ] } }
POST   /sessions                 (auth)  Body: { workspace_id,title,description,type,start_time,end_time,max_seats }  201 : { data: { session } }
POST   /sessions/{id}/join       (auth)  200 : { data: { session } }   // 409 if full / already joined
DELETE /sessions/{id}/join       (auth)  200 : { success: true }       // leave
```
> Participant payloads include `phone_number` + `whatsapp_number` so the app opens WhatsApp/dialer directly — this replaces in-app chat.

### Subscriptions / Plans
```
GET /plans                  (auth)  200 : { data: [ { id,name,price_cents,currency,included_minutes,duration_days } ] }
GET /subscriptions/active   (auth)  200 : { data: { id,plan_name,status,started_at,expires_at,remaining_minutes } | null }
```

### Attendance (QR check-in / check-out)
```
POST /visits/check-in        (auth)  Body: { workspace_id }   // decoded from scanned QR
                             201 : { data: { id,workspace_id,status:"CHECKED_IN",check_in_at } }
                             Errors: 402 no active subscription/balance, 409 already checked in
POST /visits/{id}/check-out  (auth)  200 : { data: { id,status:"CHECKED_OUT",check_in_at,check_out_at,duration_minutes,remaining_minutes_after } }
GET  /visits/open            (auth)  200 : { data: { visit } | null }   // resume on app open
```

---

## Step 4 — Implementation Roadmap

### Phase 0 — Project setup
1. `composer create-project laravel/laravel anis-backend && cd anis-backend`
2. `composer require laravel/sanctum` then `php artisan install:api` (publishes Sanctum + api routes).
3. Configure `.env` for MySQL (`DB_CONNECTION=mysql`, db name/user/pass).

### Phase 1 — Database
4. Create migrations from Step 1.2: `php artisan make:migration ...` for each table (or paste into generated files).
5. Create models: `php artisan make:model Plan`, `Subscription`, `Workspace`, `StudySession`, `WorkspaceVisit` (User exists). Add `HasUuids` + relationships from Step 1.3.
6. `php artisan migrate`.
7. Seeders: `php artisan make:seeder DemoSeeder` → insert sample Plans, Workspaces, a demo User. `php artisan db:seed`.

### Phase 2 — Auth (Sanctum, no OTP)
8. `AuthController`: `register` (hash password via `Hash::make`, `createToken`), `login` (verify with `Hash::check`, issue token), `me`, `logout` (`currentAccessToken()->delete()`).
9. Protect routes with `auth:sanctum` middleware group in `routes/api.php`.

### Phase 3 — Feature controllers (one per domain)
10. `WorkspaceController` — index (optional Haversine distance via raw select on lat/lng), show.
11. `SessionController` — index/show/store/join/leave. Enforce `max_seats` and unique participation; append `participants_count` and `is_joined` (relative to `auth()->id()`) via accessors or API Resources.
12. `SubscriptionController` — `plans`, `active`.
13. `VisitController` — `checkIn` (guard: active subscription + no open visit), `checkOut` (compute duration, decrement `remaining_minutes`), `open`.

### Phase 4 — Business-rule guards
14. Form Requests (`php artisan make:request`) validate every payload.
15. Block check-in if no `ACTIVE` subscription, `remaining_minutes <= 0`, or `expires_at` passed.
16. Wrap check-out in `DB::transaction()` so duration calc + balance decrement are atomic.
17. Subscription expiry: a scheduled command (`php artisan make:command ExpireSubscriptions`, registered in `routes/console.php` / scheduler) flips `ACTIVE → EXPIRED` on expiry or zero balance.

### Phase 5 — Hardening & deploy
18. API Resources (`php artisan make:resource`) to shape every JSON response into the `{ success,data,message }` envelope; never expose `password`.
19. Rate-limit auth routes (`throttle` middleware), enable CORS for the app origin.
20. Deploy: MySQL (PlanetScale/managed) + app on Forge/Railway/Render or a VPS. Run `php artisan migrate --force` on release, set `APP_KEY` and env.
21. Point Flutter `baseUrl` at the deployed API; implement each Repository against Step 3.

### Suggested structure
```
app/
  Models/        User Plan Subscription Workspace StudySession WorkspaceVisit
  Http/
    Controllers/Api/  AuthController WorkspaceController SessionController
                      SubscriptionController VisitController
    Requests/         RegisterRequest LoginRequest CreateSessionRequest CheckInRequest
    Resources/        UserResource WorkspaceResource SessionResource ...
database/migrations/  (Step 1.2)
database/seeders/     DemoSeeder
routes/api.php
```

---

### Security note on "no OTP"
Phone-only login is insecure alone, so this design pairs phone number with a **password** (Laravel `Hash`) and issues a **Sanctum token**. Meets the "no OTP" requirement while keeping accounts protected. Add OTP/magic-link later without schema changes.

### Laravel-specific gotcha
`session` is a reserved concept/table in Laravel, so the Sessions feature uses table **`study_sessions`** and model **`StudySession`** (API paths stay `/sessions`). Keep this in mind when wiring relationships.
```
