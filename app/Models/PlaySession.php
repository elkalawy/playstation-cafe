<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'user_id',
        'start_time',
        'actual_end_time',
        'total_cost',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',      // إضافة خاصية التحويل
        'actual_end_time' => 'datetime', // إضافة خاصية التحويل
    ];

    /**
     * Get the device that owns the session.
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the user (employee) who started the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the periods for the play session.
     */
    public function periods()
    {
        return $this->hasMany(SessionPeriod::class);
    }
}