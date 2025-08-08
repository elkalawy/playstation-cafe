<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevicePricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'controller_count',
        'price_per_hour',
    ];

    /**
     * Get the device that owns the pricing.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}