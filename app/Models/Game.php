<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // <-- 1. تم استدعاء هذا الكلاس
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage; // <-- 2. تم استدعاء واجهة التخزين

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cover_image',
        'is_game_based_playable',
    ];

    /**
     * The game-based pricing options for the game.
     */
    public function pricings(): HasMany
    {
        return $this->hasMany(GamePricing::class);
    }

    /**
     * The devices that have this game.
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class);
    }

    // ==================== بداية المُحسِّن الذكي للصور ====================
    /**
     * Get the full URL for the game's cover image.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // إذا كانت هناك صورة، قم ببناء الرابط الكامل لها
                // وإلا، قم بإرجاع رابط لصورة افتراضية
                return $value
                    ? Storage::url($value)
                    : 'https://via.placeholder.com/300x400.png?text=No+Image';
            }
        );
    }
    // ==================== نهاية المُحسِّن الذكي للصور ====================
}