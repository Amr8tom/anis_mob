<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionParticipant extends Model
{
    // Pivot table uses an auto-increment id; rows are written via attach().
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<StudySession, $this> */
    public function session(): BelongsTo
    {
        return $this->belongsTo(StudySession::class, 'session_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
