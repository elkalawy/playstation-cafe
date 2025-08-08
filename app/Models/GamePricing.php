<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamePricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'controller_count',
        'price',
        'duration_in_minutes', // <-- تم إضافة هذا
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}