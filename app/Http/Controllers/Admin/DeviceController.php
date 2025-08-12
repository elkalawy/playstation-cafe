<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Game;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        // ==================== بداية الحل النهائي للسيطرة الكاملة ====================
        $devices = Device::with([
            'games', 
            'pricings',
            // نتحكم بشكل كامل في كيفية جلب الجلسة النشطة
            'activeSession' => function ($query) {
                // نطلب منه تحديد الأعمدة التي نريدها فقط من جدول الجلسات
                $query->select('id', 'device_id', 'session_start_at', 'status');
            },
            'activeSession.user', 
            'activeSession.periods'
        ])
        ->latest()
        ->get();
        // ==================== نهاية الحل النهائي للسيطرة الكاملة ====================
                         
        return view('admin.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        Device::create($validated);

        return redirect()->route('admin.devices.index')->with('success', 'تم إنشاء الجهاز بنجاح.');
    }

    public function edit(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|in:available,busy,maintenance',
        ]);

        $device->update($validated);

        return redirect()->route('admin.devices.index')->with('success', 'تم تحديث الجهاز بنجاح.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('admin.devices.index')->with('success', 'تم حذف الجهاز بنجاح.');
    }
    
    public function manageGames(Device $device)
    {
        $games = Game::all();
        $deviceGames = $device->games->pluck('id')->toArray();
        return view('admin.devices.manage-games', compact('device', 'games', 'deviceGames'));
    }

    public function syncGames(Request $request, Device $device)
    {
        $device->games()->sync($request->input('games', []));
        return redirect()->route('admin.devices.index')->with('success', 'تم تحديث ألعاب الجهاز بنجاح.');
    }
}