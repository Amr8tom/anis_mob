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
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'whatsapp_number',
        'password',
        'role',
        'gender',
        'is_guest',
        'profile_completed_at',
        'avatar_url',
        'initials',
        'university',
        'study_field',
        'interests',
        'avatar_color_key',
        'availability',
        'rating',
        'wallet_balance',
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
            'profile_completed_at' => 'datetime',
            'interests' => 'array',
            'rating' => 'decimal:2',
            'wallet_balance' => 'decimal:2',
            'total_study_hours' => 'integer',
            'streak_days' => 'integer',
            'total_sessions' => 'integer',
        ];
    }

    // ---- Relationships ----

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
}
