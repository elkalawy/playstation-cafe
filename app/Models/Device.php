<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'status'];

    /**
     * The games that belong to the device.
     */
    public function games()
    {
        return $this->belongsToMany(Game::class, 'device_game');
    }

    /**
     * Get the pricings for the device.
     */
    public function pricings()
    {
        return $this->hasMany(DevicePricing::class);
    }

    /**
     * Get all play sessions for the device.
     */
    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }

    /**
     * Get the currently active play session for the device.
     */
    public function activeSession()
    {
        // التصحيح هنا: استخدام hasOne لجلب سجل واحد فقط
        return $this->hasOne(PlaySession::class)->where('status', 'active');
    }
}