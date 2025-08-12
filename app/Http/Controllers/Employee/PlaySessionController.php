<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DevicePricing;
use App\Models\GamePricing;
use App\Models\PlaySession;
use App\Models\SessionPeriod;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class PlaySessionController extends Controller
{
    public function start(Request $request)
    {
        $validated = $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'play_type' => ['required', Rule::in(['time', 'game'])],
            'controller_count' => ['required', 'integer', 'min:1'],
            'game_id' => ['required_if:play_type,game', 'exists:games,id'],
        ]);

        $device = Device::findOrFail($validated['device_id']);
        if ($device->status !== 'available') {
            return back()->with('error', 'هذا الجهاز غير متاح حالياً!');
        }

        if ($validated['play_type'] === 'game') {
            $pricingService = new PricingService();
            $pricing = $pricingService->findPricing(
                $validated['play_type'],
                $validated['controller_count'],
                $device->id,
                $validated['game_id'] ?? null
            );

            if (!$pricing) {
                return back()->withInput()->with('error', 'خطأ: لم يتم تحديد سعر لهذه اللعبة.');
            }
        }

        DB::transaction(function () use ($validated, $device, $request) {
            $playSession = PlaySession::create([
                'device_id' => $device->id,
                'user_id' => $request->user()->id,
                'session_start_at' => now(),
                'total_cost' => 0,
                'status' => 'active',
            ]);

            $this->createInitialPeriod($playSession, $validated);
            $device->update(['status' => 'busy']);
        });
        
        return redirect()->route('employee.dashboard')->with('success', "تم بدء الجلسة للجهاز {$device->name} بنجاح.");
    }
    
    // ==================== بداية الدالة الجديدة ====================
    public function addAnotherGame(PlaySession $playSession)
    {
        DB::transaction(function () use ($playSession) {
            // 1. إنهاء الفترة الحالية (الجيم الحالي) وحساب تكلفتها
            $this->endCurrentPeriod($playSession);

            // 2. جلب تفاصيل الفترة الأخيرة التي تم إنهاؤها للتو
            $lastPeriod = $playSession->periods()->latest('end_time')->first();

            // 3. إنشاء فترة جديدة بنفس تفاصيل الفترة السابقة
            if ($lastPeriod && $lastPeriod->play_type === 'game') {
                $data = [
                    'play_type' => 'game',
                    'game_id' => $lastPeriod->game_id,
                    'controller_count' => $lastPeriod->controller_count,
                ];
                $this->createInitialPeriod($playSession, $data);
            }
        });

        return back()->with('success', 'تمت إضافة جيم جديد بنجاح.');
    }
    // ==================== نهاية الدالة الجديدة ====================

    public function switchPlayType(Request $request, PlaySession $playSession)
    {
        $validated = $request->validate([
            'play_type' => ['required', Rule::in(['time', 'game'])],
            'controller_count' => ['required', 'integer', 'min:1'],
            'game_id' => ['required_if:play_type,game', 'exists:games,id'],
        ]);

        $pricingService = new PricingService();
        $pricing = $pricingService->findPricing(
            $validated['play_type'],
            $validated['controller_count'],
            $playSession->device_id,
            $validated['game_id'] ?? null
        );

        if (!$pricing) {
            return back()->with('error', 'خطأ: لا يمكن التبديل، لم يتم تحديد سعر لهذا الخيار.');
        }

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

        return redirect()->route('employee.dashboard')->with('success', 'تم إنهاء الجلسة بنجاح. التكلفة الإجمالية: ' . number_format($playSession->total_cost, 2) . ' جنيه.');
    }
    
    public function setAlert(Request $request, PlaySession $playSession)
    {
        $validated = $request->validate([
            'alert_in_minutes' => ['required', 'integer', 'min:1'],
        ]);

        if ($playSession->status !== 'active') {
            return back()->with('error', 'لا يمكن ضبط منبه لجلسة منتهية.');
        }

        $minutes = $validated['alert_in_minutes'];
        $alertTimestamp = now()->addMinutes((int)$minutes);

        DB::table('play_sessions')
            ->where('id', $playSession->id)
            ->update(['alert_at' => $alertTimestamp]);
        
        return back()->with('success', "تم ضبط/تعديل المنبه بنجاح بعد {$minutes} دقيقة.");
    }

    public function cancelAlert(PlaySession $playSession)
    {
        if ($playSession->status !== 'active') {
            return back()->with('error', 'لا يمكن إلغاء منبه لجلسة منتهية.');
        }
        
        DB::table('play_sessions')
            ->where('id', $playSession->id)
            ->update(['alert_at' => null]);

        return back()->with('success', 'تم إلغاء المنبه بنجاح.');
    }

    private function createInitialPeriod(PlaySession $playSession, array $data, float $initialCost = 0): void
    {
        $cost = $initialCost;
        if ($data['play_type'] === 'game') {
            $pricing = GamePricing::where('game_id', $data['game_id'])
                                ->where('controller_count', $data['controller_count'])
                                ->firstOrFail();
            $cost = $pricing->price;
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
            if ($currentPeriod->play_type === 'time') { 
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