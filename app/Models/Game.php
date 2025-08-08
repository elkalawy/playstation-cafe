<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}