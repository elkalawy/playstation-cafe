<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaySession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // ==================== بداية الجزء الذي تم تعديله ====================
    protected $fillable = [
        'device_id',
        'user_id',
        'session_start_at',
        'alert_at', // <-- تم حذف الأعمدة غير الضرورية
        'actual_end_time',
        'total_cost',
        'status',
    ];
    // ==================== نهاية الجزء الذي تم تعديله ====================

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // ==================== بداية الجزء الذي تم تعديله ====================
    protected $casts = [
        'session_start_at' => 'datetime',
        'alert_at' => 'datetime', // <-- تم حذف الأعمدة غير الضرورية
        'actual_end_time' => 'datetime',
    ];
    // ==================== نهاية الجزء الذي تم تعديله ====================

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(SessionPeriod::class);
    }
}