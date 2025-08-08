<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'play_session_id',
        'start_time',
        'end_time',
    ];

    /**
     * Get the main play session that this game belongs to.
     */
    public function playSession(): BelongsTo
    {
        return $this->belongsTo(PlaySession::class);
    }
}