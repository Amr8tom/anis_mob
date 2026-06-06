# anis — Backend Design & Implementation Guide

**Stack:** Node.js + Express + Prisma ORM + PostgreSQL + JWT auth
**Frontend:** Flutter, Clean Architecture (Entities / Models / Repositories / Use Cases)

> Scope guardrails (by design): shared coworking only — **no private/manager offices**, **no in-app chat** (contact via displayed phone/WhatsApp), **no OTP** (phone + password auth). One unified subscription grants access to **any** workspace in the network.

---

## Step 1 — Database Schema & Relationships

### 1.1 Relationship map

```
User ──1:M──> Subscription        (a user has a subscription history; one ACTIVE at a time)
User ──1:M──> Session             (sessions a user created / hosts)
User ──M:N──> Session             (sessions a user joined)        via SessionParticipant
User ──1:M──> WorkspaceVisit      (check-in / check-out records)
Workspace ──1:M──> Session        (sessions happen inside a workspace)
Workspace ──1:M──> WorkspaceVisit (visits / attendance at a workspace)
Plan ──1:M──> Subscription        (a plan template instantiated per user)
```

Plain English:

- A **User** buys a **Subscription** (network-wide). The subscription is *not* tied to a single workspace — that is the core product rule.
- A **Workspace** is a physical shared space. It contains many **Sessions**.
- A **Session** belongs to exactly one Workspace and is created by one User (host). Many Users can **join** it → many-to-many through `SessionParticipant`.
- A **WorkspaceVisit** is one check-in→check-out cycle (the QR feature). It deducts studied minutes from the user's active subscription balance.
- A **Plan** is the catalog template (name, price, included minutes/days). Each Subscription is one purchased instance of a Plan.

### 1.2 Prisma schema (`schema.prisma`)

```prisma
generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
}

// ----------------------------- USERS -----------------------------
enum UserRole {
  USER
  ADMIN
}

enum Availability {
  ONLINE
  BUSY
  OFFLINE
}

model User {
  id            String   @id @default(uuid())
  fullName      String
  phoneNumber   String   @unique          // login identifier
  email         String?  @unique          // optional alternate login identifier
  whatsappNumber String                   // shown for out-of-app contact
  passwordHash  String                    // no OTP -> password set at registration
  role          UserRole @default(USER)   // app reads user.role on login
  isGuest       Boolean  @default(false)  // guest-login path
  avatarUrl     String?
  initials      String?                   // shown in avatars
  university    String?                   // shown on sessions / buddy / profile
  studyField    String?                   // buddy: field of study
  interests     Json     @default("[]")   // buddy: list<string>
  avatarColorKey String  @default("blue") // buddy/profile avatar tint
  availability  Availability @default(OFFLINE)
  rating        Float    @default(0)      // buddy rating 0..5
  // gamification + wallet (profile & home screens)
  walletBalance   Float @default(0)
  totalStudyHours Int   @default(0)
  streakDays      Int   @default(0)
  totalSessions   Int   @default(0)
  createdAt     DateTime @default(now())
  updatedAt     DateTime @updatedAt

  subscriptions   Subscription[]
  hostedSessions  Session[]            @relation("SessionHost")
  participations  SessionParticipant[]
  visits          WorkspaceVisit[]
  badges          UserBadge[]
  ledgers         SubscriptionLedger[]

  @@map("users")
}

// -------------------------- PLANS / SUBS --------------------------
enum PlanTier {
  FREE
  SILVER
  GOLD
}

model Plan {
  id             String   @id @default(uuid())
  name           String                       // e.g. "Monthly", "20 Hours"
  tier           PlanTier @default(FREE)       // app subscriptionType: free|silver|gold
  description    String?
  priceCents     Int
  currency       String   @default("EGP")
  // Access model: time-balance OR duration. Use whichever fits a plan.
  includedMinutes Int?                         // for time-balance plans
  durationDays    Int?                         // for unlimited-time, date-bound plans
  isActive       Boolean  @default(true)
  createdAt      DateTime @default(now())

  subscriptions  Subscription[]

  @@map("plans")
}

enum SubscriptionStatus {
  ACTIVE
  EXPIRED
  CANCELLED
}

model Subscription {
  id               String             @id @default(uuid())
  userId           String
  planId           String
  status           SubscriptionStatus @default(ACTIVE)
  startedAt        DateTime           @default(now())
  expiresAt        DateTime?                       // for durationDays plans
  remainingMinutes Int?                            // for includedMinutes plans; decremented on check-out
  createdAt        DateTime           @default(now())
  updatedAt        DateTime           @updatedAt

  user  User @relation(fields: [userId], references: [id], onDelete: Cascade)
  plan  Plan @relation(fields: [planId], references: [id])
  ledgers SubscriptionLedger[]

  @@index([userId, status])
  @@map("subscriptions")
}

// ----------------------------- WORKSPACES -----------------------------
enum WorkspaceStatus {
  OPEN
  BUSY
  FULL
  CLOSED
}

model Workspace {
  id          String   @id @default(uuid())
  name        String
  description String?
  address     String
  latitude    Float?
  longitude   Float?
  coverImageUrl String?
  galleryImages Json   @default("[]")   // list<string> image urls
  amenities   Json     @default("[]")   // list<string>: wifi|ac|coffee|printing|quiet
  status      WorkspaceStatus @default(OPEN)
  capacity    Int?
  openTime    String?                 // "08:00"
  closeTime   String?                 // "23:00"
  dayCalculationHours Int @default(8)  // one subscription day is counted after this many hours
  isActive    Boolean  @default(true)
  createdAt   DateTime @default(now())
  updatedAt   DateTime @updatedAt

  sessions Session[]
  visits   WorkspaceVisit[]
  drinks   WorkspaceDrink[]

  @@map("workspaces")
}

// Menu/drinks served at a workspace (app: WorkspaceDrinkEntity)
model WorkspaceDrink {
  id          String   @id @default(uuid())
  workspaceId String
  name        String
  icon        String                   // icon key
  priceCents  Int
  createdAt   DateTime @default(now())

  workspace Workspace @relation(fields: [workspaceId], references: [id], onDelete: Cascade)

  @@index([workspaceId])
  @@map("workspace_drinks")
}

// ----------------------------- SESSIONS -----------------------------
enum SessionType {
  STUDY_GROUP
  EVENT
  FOCUS_BLOCK
}

enum SessionStatus {
  UPCOMING
  IN_PROGRESS              // app emits "inProgress"
  ENDED                    // app emits "ended"
  CANCELLED
}

// NOTE: "Buddy session" == "Study session" — one table, two projections.
// Founder = host (User). Buddy "members" = SessionParticipant rows.
// Display-only fields the app reads are stored so the API can echo them.
model Session {
  id          String        @id @default(uuid())
  workspaceId String
  hostId      String                       // = founder / buddy
  title       String                       // app: title / topic
  subject     String?                      // buddy: subject area
  description String?
  rules       Json     @default("[]")      // buddy: list<string>
  gift        String?                      // buddy: optional gift/reward
  type        SessionType   @default(STUDY_GROUP)
  status      SessionStatus @default(UPCOMING)
  startTime   DateTime
  endTime     DateTime?
  maxSeats    Int?                          // = maxParticipants / maxCapacity
  timeLabel   String?                       // display label e.g. "2:00 م"
  tagLabel    String?                       // home card short tag e.g. "فيز"
  tagColorKey String?                       // 'blue'|'yellow'|'pink'|'green'
  createdAt   DateTime      @default(now())
  updatedAt   DateTime      @updatedAt

  workspace    Workspace            @relation(fields: [workspaceId], references: [id], onDelete: Cascade)
  host         User                 @relation("SessionHost", fields: [hostId], references: [id])
  participants SessionParticipant[]

  @@index([workspaceId, status])
  @@map("sessions")
}

model SessionParticipant {
  id        String   @id @default(uuid())
  sessionId String
  userId    String
  joinedAt  DateTime @default(now())

  session Session @relation(fields: [sessionId], references: [id], onDelete: Cascade)
  user    User    @relation(fields: [userId], references: [id], onDelete: Cascade)

  @@unique([sessionId, userId])     // a user joins a session once
  @@map("session_participants")
}

// ------------------------ ATTENDANCE / QR VISITS ------------------------
enum VisitStatus {
  CHECKED_IN
  CHECKED_OUT
}

model WorkspaceVisit {
  id              String      @id @default(uuid())
  userId          String
  workspaceId     String
  subscriptionId  String?                       // which subscription was charged
  status          VisitStatus @default(CHECKED_IN)
  checkInAt       DateTime    @default(now())
  checkOutAt      DateTime?
  durationMinutes Int?                           // computed on check-out
  createdAt       DateTime    @default(now())

  user      User      @relation(fields: [userId], references: [id], onDelete: Cascade)
  workspace Workspace @relation(fields: [workspaceId], references: [id], onDelete: Cascade)

  @@index([userId, status])
  @@map("workspace_visits")
}

// ----------------------------- BADGES (profile gamification) -----------------------------
model Badge {
  id        String   @id @default(uuid())
  key       String   @unique            // 'streak'|'hours'|'sessions'|'top'
  label     String
  iconKey   String
  createdAt DateTime @default(now())

  users UserBadge[]

  @@map("badges")
}

model UserBadge {
  id       String   @id @default(uuid())
  userId   String
  badgeId  String
  earnedAt DateTime @default(now())

  user  User  @relation(fields: [userId], references: [id], onDelete: Cascade)
  badge Badge @relation(fields: [badgeId], references: [id], onDelete: Cascade)

  @@unique([userId, badgeId])
  @@map("user_badges")
}

// -------------------- SUBSCRIPTION LEDGER (immutable audit of balance changes) --------------------
model SubscriptionLedger {
  id             String   @id @default(uuid())
  subscriptionId String
  visitId        String?
  userId         String
  changeMinutes  Int                       // negative = deduction, positive = top-up
  balanceAfter   Int
  reason         String                    // WORKSPACE_VISIT | TOP_UP | ADJUSTMENT | REFUND
  createdAt      DateTime @default(now())   // append-only; never updated

  subscription Subscription @relation(fields: [subscriptionId], references: [id], onDelete: Cascade)
  user         User         @relation(fields: [userId], references: [id], onDelete: Cascade)

  @@index([subscriptionId, createdAt])
  @@map("subscription_ledgers")
}
```

### 1.3 Notes on the QR check-in logic

The QR feature your Flutter app is already wiring maps directly onto `WorkspaceVisit`:

1. **Check-in:** User scans a workspace QR (the QR encodes `workspaceId`). Backend verifies the user has an `ACTIVE` subscription with remaining balance/validity, then creates a `WorkspaceVisit { status: CHECKED_IN, checkInAt: now }`. A user may only have one open (CHECKED_IN) visit at a time.
2. **Check-out:** User taps "Leave workspace." Backend sets `checkOutAt = now`, computes `durationMinutes`, sets `status: CHECKED_OUT`, and decrements `subscription.remainingMinutes` by the duration (for time-balance plans).

---

## Step 2 — Clean Architecture Mapping (Flutter / Dart)

For each domain object: **Entity** (pure Dart, no JSON), **Model** (extends/maps Entity, has `fromJson`/`toJson`), **Repository** (abstract interface in domain), **Use Cases** (one action each).

### 2.1 Entities (domain layer)

```dart
// domain/entities/user_entity.dart
class UserEntity {
  final String id;
  final String fullName;
  final String phoneNumber;
  final String whatsappNumber;
  final String? avatarUrl;
  const UserEntity({
    required this.id,
    required this.fullName,
    required this.phoneNumber,
    required this.whatsappNumber,
    this.avatarUrl,
  });
}

// domain/entities/workspace_entity.dart
class WorkspaceEntity {
  final String id;
  final String name;
  final String? description;
  final String address;
  final double? latitude;
  final double? longitude;
  final String? coverImageUrl;
  final int? capacity;
  final int dayCalculationHours; // one subscription day = this many study hours or more
  const WorkspaceEntity({ /* ... */ });
}

// domain/entities/session_entity.dart
class SessionEntity {
  final String id;
  final String workspaceId;
  final String hostId;
  final String title;
  final String? description;
  final String type;        // STUDY_GROUP | EVENT | FOCUS_BLOCK
  final String status;      // UPCOMING | ONGOING | COMPLETED | CANCELLED
  final DateTime startTime;
  final DateTime endTime;
  final int? maxSeats;
  final int participantsCount;
  final bool isJoined;       // computed for the current user
  const SessionEntity({ /* ... */ });
}

// domain/entities/subscription_entity.dart
class SubscriptionEntity {
  final String id;
  final String planName;
  final String status;            // ACTIVE | EXPIRED | CANCELLED
  final DateTime startedAt;
  final DateTime? expiresAt;
  final int? remainingMinutes;
  const SubscriptionEntity({ /* ... */ });
}

// domain/entities/workspace_visit_entity.dart
class WorkspaceVisitEntity {
  final String id;
  final String workspaceId;
  final String status;            // CHECKED_IN | CHECKED_OUT
  final DateTime checkInAt;
  final DateTime? checkOutAt;
  final int? durationMinutes;
  const WorkspaceVisitEntity({ /* ... */ });
}
```

### 2.2 Models (data layer — JSON ⇄ Dart)

Each model mirrors the JSON contract in Step 3. Example:

```dart
// data/models/session_model.dart
class SessionModel extends SessionEntity {
  const SessionModel({ /* same fields */ }) : super(/* ... */);

  factory SessionModel.fromJson(Map<String, dynamic> json) => SessionModel(
    id: json['id'],
    workspaceId: json['workspaceId'],
    hostId: json['hostId'],
    title: json['title'],
    description: json['description'],
    type: json['type'],
    status: json['status'],
    startTime: DateTime.parse(json['startTime']),
    endTime: DateTime.parse(json['endTime']),
    maxSeats: json['maxSeats'],
    participantsCount: json['participantsCount'] ?? 0,
    isJoined: json['isJoined'] ?? false,
  );

  Map<String, dynamic> toJson() => {
    'workspaceId': workspaceId,
    'title': title,
    'description': description,
    'type': type,
    'startTime': startTime.toIso8601String(),
    'endTime': endTime.toIso8601String(),
    'maxSeats': maxSeats,
  };
}
```

### 2.3 Repository interfaces (domain layer)

```dart
abstract class AuthRepository {
  Future<UserEntity> register(RegisterParams params);
  Future<UserEntity> login(String phoneNumber, String password);
  Future<UserEntity> getCurrentUser();
  Future<void> logout();
}

abstract class WorkspaceRepository {
  Future<List<WorkspaceEntity>> getWorkspaces({double? lat, double? lng});
  Future<WorkspaceEntity> getWorkspaceById(String id);
}

abstract class SessionRepository {
  Future<List<SessionEntity>> getSessions({String? workspaceId});
  Future<SessionEntity> getSessionById(String id);
  Future<SessionEntity> createSession(CreateSessionParams params);
  Future<void> joinSession(String sessionId);
  Future<void> leaveSession(String sessionId);
}

abstract class SubscriptionRepository {
  Future<SubscriptionEntity?> getActiveSubscription();
  Future<List<PlanEntity>> getPlans();
}

abstract class AttendanceRepository {
  Future<WorkspaceVisitEntity> checkIn(String workspaceId);   // QR scan result
  Future<WorkspaceVisitEntity> checkOut(String visitId);       // "Leave workspace"
  Future<WorkspaceVisitEntity?> getOpenVisit();
}
```

### 2.4 Use Cases (one action each)

```
Auth        : RegisterUseCase, LoginUseCase, GetCurrentUserUseCase, LogoutUseCase
Workspaces  : GetWorkspacesUseCase, GetWorkspaceByIdUseCase
Sessions    : GetSessionsUseCase, GetSessionByIdUseCase, CreateSessionUseCase,
              JoinSessionUseCase, LeaveSessionUseCase
Subscription: GetActiveSubscriptionUseCase, GetPlansUseCase
Attendance  : CheckInUseCase, CheckOutUseCase, GetOpenVisitUseCase
```

Example:

```dart
class CheckInUseCase {
  final AttendanceRepository repo;
  CheckInUseCase(this.repo);
  Future<WorkspaceVisitEntity> call(String workspaceId) =>
      repo.checkIn(workspaceId);
}
```

---

## Step 3 — Remote Data Sources (REST API contracts)

Base URL: `/api/v1`. Auth: `Authorization: Bearer <JWT>` on all protected routes. All responses wrapped as `{ "success": bool, "data": ..., "message": string }`.

### Auth

```
POST /auth/register
Body: { "fullName","phoneNumber","whatsappNumber","password" }
200 : { "data": { "user": {…}, "token": "JWT" } }

POST /auth/login
Body: { "phoneNumber","password" }
200 : { "data": { "user": {…}, "token": "JWT" } }

GET  /auth/me                       (protected)
200 : { "data": { user } }
```

### Workspaces

```
GET  /workspaces?lat=..&lng=..      (protected)
200 : { "data": [ { id,name,address,latitude,longitude,coverImageUrl,capacity,dayCalculationHours,distanceKm } ] }

GET  /workspaces/:id                (protected)
200 : { "data": { workspace, "activeSessionsCount": int } }
```

### Sessions

```
GET  /sessions?workspaceId=..       (protected)
200 : { "data": [ { id,workspaceId,hostId,title,type,status,startTime,endTime,maxSeats,participantsCount,isJoined } ] }

GET  /sessions/:id                  (protected)
200 : { "data": { session, "participants": [ {id,fullName,phoneNumber,whatsappNumber,avatarUrl} ] } }

POST /sessions                      (protected)
Body: { "workspaceId","title","description","type","startTime","endTime","maxSeats" }
201 : { "data": { session } }

POST /sessions/:id/join             (protected)
200 : { "data": { session } }          // 409 if full or already joined

DELETE /sessions/:id/join           (protected)  // leave
200 : { "success": true }
```

> Contact details (`phoneNumber`, `whatsappNumber`) are returned in the session/participants payloads so the app can open WhatsApp/dialer directly — this replaces in-app chat.

### Subscription / Plans

```
GET  /plans                         (protected)
200 : { "data": [ { id,name,priceCents,currency,includedMinutes,durationDays } ] }

GET  /subscriptions/active          (protected)
200 : { "data": { id,planName,status,startedAt,expiresAt,remainingMinutes } | null }
```

### Attendance (QR check-in / check-out)

```
POST /visits/check-in               (protected)
Body: { "workspaceId" }             // decoded from scanned QR
201 : { "data": { id,workspaceId,status:"CHECKED_IN",checkInAt } }
Errors: 402 no active subscription / no balance, 409 already checked in elsewhere

POST /visits/:id/check-out          (protected)
200 : { "data": { id,status:"CHECKED_OUT",checkInAt,checkOutAt,durationMinutes,
                  "remainingMinutesAfter": int } }

GET  /visits/open                   (protected)   // resume an in-progress visit on app open
200 : { "data": { visit } | null }
```

---

## Step 4 — Implementation Roadmap

### Phase 0 — Project setup
1. `mkdir anis-backend && cd anis-backend && npm init -y`
2. `npm i express prisma @prisma/client jsonwebtoken bcrypt zod cors dotenv`
3. `npm i -D typescript ts-node-dev @types/express @types/node @types/jsonwebtoken @types/bcrypt`
4. `npx tsc --init` and `npx prisma init` (creates `prisma/schema.prisma` + `.env`).

### Phase 1 — Database
5. Paste the schema from Step 1.2 into `prisma/schema.prisma`.
6. Set `DATABASE_URL` in `.env` (local Postgres or a managed one — Neon/Supabase/Railway).
7. `npx prisma migrate dev --name init` → creates tables + Prisma client.
8. Write `prisma/seed.ts` to insert a few Plans, Workspaces, and a demo User. Run with `npx prisma db seed`.

### Phase 2 — Core server
9. `src/server.ts`: Express app, `cors`, `express.json()`, mount `/api/v1` router.
10. `src/lib/prisma.ts`: a single shared `PrismaClient` instance.
11. `src/middleware/auth.ts`: verify JWT, attach `req.userId`.
12. `src/lib/validate.ts`: Zod schemas per endpoint body.

### Phase 3 — Feature modules (one folder per domain: `routes`, `controller`, `service`)
13. **Auth:** register (hash password with bcrypt, issue JWT), login, `me`.
14. **Workspaces:** list (optional Haversine distance sort by lat/lng), detail.
15. **Sessions:** list/detail/create/join/leave. Enforce `maxSeats` and unique participation; compute `participantsCount` and `isJoined` for the requesting user.
16. **Subscriptions/Plans:** list plans, get active subscription.
17. **Attendance:** check-in (guard: active subscription + no open visit), check-out (compute duration, decrement `remainingMinutes` in a transaction).

### Phase 4 — Business-rule guards
18. Block check-in if no `ACTIVE` subscription, or `remainingMinutes <= 0`, or `expiresAt < now`.
19. Use a Prisma `$transaction` on check-out so duration calc + balance decrement are atomic.
20. Auto-expire subscriptions: a daily cron (or lazy check on read) flips `ACTIVE → EXPIRED` when `expiresAt` passes or balance hits zero.

### Phase 5 — Hardening & deploy
21. Centralized error handler returning the `{ success,data,message }` envelope.
22. Rate-limit `/auth/*`, validate all inputs with Zod, hash passwords, never return `passwordHash`.
23. Add request logging (`pino`/`morgan`).
24. Deploy: Postgres on Neon/Supabase, API on Railway/Render/Fly.io. Set env vars, run `prisma migrate deploy` on release.
25. Point the Flutter `baseUrl` at the deployed API; wire each Repository implementation to the endpoints in Step 3.

### Suggested folder structure
```
src/
  server.ts
  lib/        prisma.ts  jwt.ts  validate.ts
  middleware/ auth.ts    error.ts
  modules/
    auth/        auth.routes.ts  auth.controller.ts  auth.service.ts
    workspaces/  ...
    sessions/    ...
    subscriptions/ ...
    attendance/  ...
prisma/
  schema.prisma  seed.ts
```

---

### Security note on "no OTP"
Phone-number-only login is insecure on its own, so this design pairs the phone number with a **password set at registration** (bcrypt-hashed) and issues a **JWT**. This keeps your "no OTP" requirement while still protecting accounts. If you later want passwordless login, add OTP or magic-link then — the schema doesn't need to change.
```
