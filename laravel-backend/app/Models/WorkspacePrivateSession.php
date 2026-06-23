<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspacePrivateSessionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class WorkspacePrivateSession extends Model
{
    /** @use HasFactory<WorkspacePrivateSessionFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'capacity' => 'integer',
            'price_cents' => 'integer',
            'instructor_payout_value' => 'integer',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_owner_id');
    }

    /** @return BelongsTo<WorkspaceOwner, $this> */
    public function workspaceOwner(): BelongsTo
    {
        return $this->belongsTo(WorkspaceOwner::class, 'created_by_workspace_owner_id');
    }

    /** @return HasMany<WorkspacePrivateSessionAttendee, $this> */
    public function attendees(): HasMany
    {
        return $this->hasMany(WorkspacePrivateSessionAttendee::class);
    }

    /** @return BelongsTo<WorkspaceCenterTeacher, $this> */
    public function centerTeacher(): BelongsTo
    {
        return $this->belongsTo(WorkspaceCenterTeacher::class, 'center_teacher_id');
    }

    /** @return BelongsTo<WorkspaceCenterSubject, $this> */
    public function centerSubject(): BelongsTo
    {
        return $this->belongsTo(WorkspaceCenterSubject::class, 'center_subject_id');
    }

    /** @return BelongsTo<WorkspaceCenterGradeLevel, $this> */
    public function centerGradeLevel(): BelongsTo
    {
        return $this->belongsTo(WorkspaceCenterGradeLevel::class, 'center_grade_level_id');
    }

    public function priceEgp(): float
    {
        return $this->price_cents / 100;
    }

    /**
     * @return array{
     *     invited:int,
     *     attended:int,
     *     not_attended:int,
     *     expected_revenue_cents:int,
     *     actual_revenue_cents:int,
     *     qr_checkins:int,
     *     owner_checkins:int
     * }
     */
    public function summary(): array
    {
        if ($this->relationLoaded('attendees')) {
            $attendees = $this->attendees;
            $invited = $attendees->count();
            $attended = $attendees->where('status', 'attended');

            return [
                'invited' => $invited,
                'attended' => $attended->count(),
                'not_attended' => max(0, $invited - $attended->count()),
            'expected_revenue_cents' => $invited * (int) $this->price_cents,
            'actual_revenue_cents' => (int) $attended->sum('amount_cents'),
            'instructor_payout_cents' => $this->calculateInstructorPayoutCents($attended->count(), (int) $attended->sum('amount_cents')),
            'center_net_cents' => max(0, (int) $attended->sum('amount_cents') - $this->calculateInstructorPayoutCents($attended->count(), (int) $attended->sum('amount_cents'))),
            'qr_checkins' => $attended->where('checked_in_method', 'qr')->count(),
            'owner_checkins' => $attended->where('checked_in_method', 'owner')->count(),
        ];
        }

        $invited = (int) $this->attendees()->count();
        $attended = (int) $this->attendees()->where('status', 'attended')->count();

        $actualRevenue = (int) $this->attendees()->where('status', 'attended')->sum('amount_cents');
        $instructorPayout = $this->calculateInstructorPayoutCents($attended, $actualRevenue);

        return [
            'invited' => $invited,
            'attended' => $attended,
            'not_attended' => max(0, $invited - $attended),
            'expected_revenue_cents' => $invited * (int) $this->price_cents,
            'actual_revenue_cents' => $actualRevenue,
            'instructor_payout_cents' => $instructorPayout,
            'center_net_cents' => max(0, $actualRevenue - $instructorPayout),
            'qr_checkins' => (int) $this->attendees()->where('status', 'attended')->where('checked_in_method', 'qr')->count(),
            'owner_checkins' => (int) $this->attendees()->where('status', 'attended')->where('checked_in_method', 'owner')->count(),
        ];
    }

    public function calculateInstructorPayoutCents(int $attendedCount, int $actualRevenueCents): int
    {
        return match ($this->instructor_payout_type) {
            'percentage' => (int) round($actualRevenueCents * ((int) $this->instructor_payout_value / 10000)),
            'per_attendee_fixed' => $attendedCount * (int) $this->instructor_payout_value,
            'session_fixed' => $attendedCount > 0 ? (int) $this->instructor_payout_value : 0,
            default => 0,
        };
    }

    public function instructorPayoutLabel(): string
    {
        return match ($this->instructor_payout_type) {
            'percentage' => rtrim(rtrim(number_format(((int) $this->instructor_payout_value) / 100, 2), '0'), '.').'%',
            'per_attendee_fixed' => number_format(((int) $this->instructor_payout_value) / 100, 2).' ج.م / طالب',
            'session_fixed' => number_format(((int) $this->instructor_payout_value) / 100, 2).' ج.م للجلسة',
            default => 'لا يوجد',
        };
    }
}
