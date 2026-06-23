<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Availability;
use App\Enums\Gender;
use App\Enums\SubscriptionStatus;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'phone_number',
        'phone_number_normalized',
        'email',
        'whatsapp_number',
        'password',
        'locale',
        'role',
        'gender',
        'is_guest',
        'admin_permissions',
        'admin_mfa_secret',
        'admin_mfa_enabled',
        'admin_failed_login_attempts',
        'admin_locked_until',
        'profile_completed_at',
        'avatar_url',
        'initials',
        'university',
        'study_field',
        'interests',
        'avatar_color_key',
        'availability',
        'rating',
        'total_study_hours',
        'streak_days',
        'total_sessions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
            'gender' => Gender::class,
            'availability' => Availability::class,
            'is_guest' => 'boolean',
            'admin_permissions' => 'array',
            'admin_mfa_secret' => 'encrypted',
            'admin_mfa_enabled' => 'boolean',
            'admin_failed_login_attempts' => 'integer',
            'admin_locked_until' => 'datetime',
            'profile_completed_at' => 'datetime',
            'interests' => 'array',
            'rating' => 'decimal:2',
            'total_study_hours' => 'integer',
            'streak_days' => 'integer',
            'total_sessions' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $user): void {
            if ($user->isDirty('phone_number')) {
                $user->phone_number_normalized = self::normalizePhone($user->phone_number);
            }
        });
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $normalized = preg_replace('/\D+/', '', (string) $phone) ?? '';

        return $normalized === '' ? null : $normalized;
    }

    // ---- Relationships ----

    /** @return HasOne<Workspace, $this> */
    public function ownedWorkspace(): HasOne
    {
        return $this->hasOne(Workspace::class, 'owner_id');
    }

    /** @return HasMany<Subscription, $this> */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /** @return HasOne<Subscription, $this> */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->latestOfMany();
    }

    /** @return HasMany<StudySession, $this> */
    public function hostedSessions(): HasMany
    {
        return $this->hasMany(StudySession::class, 'host_id');
    }

    /** @return BelongsToMany<StudySession, $this> */
    public function joinedSessions(): BelongsToMany
    {
        return $this->belongsToMany(StudySession::class, 'session_participants', 'user_id', 'session_id')
            ->withPivot('joined_at');
    }

    /** @return HasMany<WorkspaceVisit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(WorkspaceVisit::class);
    }

    /** @return BelongsToMany<Badge, $this> */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id')
            ->withPivot('earned_at');
    }

    /** @return HasMany<SubscriptionLedger, $this> */
    public function ledgers(): HasMany
    {
        return $this->hasMany(SubscriptionLedger::class);
    }

    /** @return HasMany<WorkspaceSubscription, $this> */
    public function workspaceSubscriptions(): HasMany
    {
        return $this->hasMany(WorkspaceSubscription::class);
    }

    /** @return HasMany<DeviceToken, $this> */
    public function devices(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * Targets for the FCM notification channel: every registered device token.
     *
     * @return array<int, string>
     */
    public function routeNotificationForFcm(): array
    {
        return $this->devices()->pluck('token')->all();
    }
}
