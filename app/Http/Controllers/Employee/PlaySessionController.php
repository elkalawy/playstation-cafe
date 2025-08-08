<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DevicePricing;
use App\Models\GamePricing;
use App\Models\PlaySession;
use App\Models\SessionPeriod; // تأكد من إضافة هذا السطر
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlaySessionController extends Controller
{
    public function start(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'play_type' => ['required', Rule::in(['time', 'game'])],
            'time_type' => 'required_if:play_type,time|in:open,fixed',
            'duration_in_minutes' => 'required_if:time_type,fixed|integer|min:15',
            'controller_count' => 'required|integer|min:1',
            'game_id' => 'required_if:play_type,game|exists:games,id',
        ]);

        $device = Device::findOrFail($validated['device_id']);
        if ($device->status !== 'available') {
            return back()->with('error', 'هذا الجهاز غير متاح حالياً!');
        }

        DB::transaction(function () use ($validated, $device, $request) {
            $startTime = now();
            $expectedEndTime = null;
            $totalCost = 0;
            $duration = null;
            
            if ($validated['play_type'] === 'time' && $validated['time_type'] === 'fixed') {
                $duration = $validated['duration_in_minutes'];
                $expectedEndTime = $startTime->copy()->addMinutes($duration);
                $pricing = DevicePricing::where('device_id', $device->id)
                                        ->where('controller_count', $validated['controller_count'])
                                        ->first();
                if ($pricing) {
                    $totalCost = ($duration / 60) * $pricing->price_per_hour;
                }
            }

            $playSession = PlaySession::create([
                'device_id' => $device->id,
                'user_id' => $request->user()->id,
                'start_time' => $startTime,
                'expected_end_time' => $expectedEndTime,
                'total_cost' => $totalCost,
                'status' => 'active',
                'duration_in_minutes' => $duration,
            ]);

            $this->createInitialPeriod($playSession, $validated, $totalCost);
            $device->update(['status' => 'busy']);
        });

        return redirect()->route('employee.dashboard')->with('success', "تم بدء الجلسة للجهاز {$device->name} بنجاح.");
    }

    public function switchPlayType(Request $request, PlaySession $playSession)
    {
        $validated = $request->validate([
            'play_type' => ['required', Rule::in(['time', 'game'])],
            'controller_count' => 'required|integer|min:1',
            'game_id' => 'required_if:play_type,game|exists:games,id',
        ]);

        DB::transaction(function () use ($playSession, $validated) {
            $this->endCurrentPeriod($playSession);
            $this->createInitialPeriod($playSession, $validated);
        });

        return back()->with('success', 'تم تغيير نوع اللعب بنجاح.');
    }

    public function end(PlaySession $playSession)
    {
        DB::transaction(function () use ($playSession) {
            $this->endCurrentPeriod($playSession);
            $totalCost = $playSession->periods()->sum('cost');
            $playSession->update([
                'actual_end_time' => now(),
                'total_cost' => $totalCost,
                'status' => 'completed',
            ]);
            $playSession->device->update(['status' => 'available']);
        });

        return redirect()->route('employee.dashboard')->with('success', 'تم إنهاء الجلسة بنجاح. التكلفة الإجمالية: ' . number_format($playSession->total_cost, 2));
    }

    private function createInitialPeriod(PlaySession $playSession, array $data, float $initialCost = 0): void
    {
        $cost = $initialCost;
        if ($data['play_type'] === 'game') {
            $pricing = GamePricing::where('game_id', $data['game_id'])
                                ->where('controller_count', $data['controller_count'])
                                ->first();
            $cost = $pricing->price ?? 0;
        }

        $playSession->periods()->create([
            'start_time' => now(),
            'play_type' => $data['play_type'],
            'game_id' => $data['game_id'] ?? null,
            'controller_count' => $data['controller_count'],
            'cost' => $cost,
        ]);
    }

    private function endCurrentPeriod(PlaySession $playSession): void
    {
        $currentPeriod = $playSession->periods()->whereNull('end_time')->first();
        if ($currentPeriod) {
            $endTime = now();
            $cost = $currentPeriod->cost;
            if ($currentPeriod->play_type === 'time' && !$playSession->duration_in_minutes) {
                $pricing = DevicePricing::where('device_id', $playSession->device_id)
                                        ->where('controller_count', $currentPeriod->controller_count)
                                        ->first();
                if ($pricing && $pricing->price_per_hour > 0) {
                    $durationInMinutes = $currentPeriod->start_time->diffInMinutes($endTime);
                    $cost = ($durationInMinutes / 60) * $pricing->price_per_hour;
                } else {
                    $cost = 0;
                }
            }
            $currentPeriod->update(['end_time' => $endTime, 'cost' => $cost]);
        }
    }
}