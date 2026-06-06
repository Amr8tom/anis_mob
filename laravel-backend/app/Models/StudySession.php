<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudySession extends Model
{
    /** @use HasFactory<\Database\Factories\StudySessionFactory> */
    use HasFactory, HasUuids;

    protected $table = 'study_sessions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => SessionType::class,
            'status' => SessionStatus::class,
            'rules' => 'array',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'max_seats' => 'integer',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<User, $this> */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /** @return BelongsToMany<User, $this> */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'session_participants', 'session_id', 'user_id')
            ->withPivot('joined_at');
    }

    /** @param Builder<StudySession> $query */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('status', SessionStatus::UPCOMING->value);
    }
}
