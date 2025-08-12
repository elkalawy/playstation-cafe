<?php

namespace App\Services;

use App\Models\DevicePricing;
use App\Models\GamePricing;
use Illuminate\Database\Eloquent\Model;

class PricingService
{
    /**
     * Finds the correct pricing model based on the play type and provided data.
     *
     * @param string $playType 'time' or 'game'
     * @param int $controllerCount
     * @param int|null $deviceId
     * @param int|null $gameId
     * @return Model|null The pricing model (DevicePricing or GamePricing) or null if not found.
     */
    public function findPricing(string $playType, int $controllerCount, ?int $deviceId, ?int $gameId): ?Model
    {
        if ($playType === 'time') {
            if (!$deviceId) {
                return null;
            }
            return DevicePricing::where('device_id', $deviceId)
                                ->where('controller_count', $controllerCount)
                                ->first();
        }

        if ($playType === 'game') {
            if (!$gameId) {
                return null;
            }
            return GamePricing::where('game_id', $gameId)
                              ->where('controller_count', $controllerCount)
                              ->first();
        }

        return null;
    }
}