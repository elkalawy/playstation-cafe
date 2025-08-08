<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'play_session_id',
        'start_time',
        'end_time',
        'play_type',
        'game_id',
        'controller_count',
        'cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime', // إضافة خاصية التحويل
        'end_time' => 'datetime',   // إضافة خاصية التحويل
    ];

    /**
     * Get the play session that owns the period.
     */
    public function playSession()
    {
        return $this->belongsTo(PlaySession::class);
    }

    /**
     * Get the game associated with the period.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}