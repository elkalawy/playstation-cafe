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
        $devices = Device::with('activeSession.user')->latest()->get();
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
        return redirect()->route('admin.devices.index')->with('success', 'تمت إضافة الجهاز بنجاح!');
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
        return redirect()->route('admin.devices.index')->with('success', 'تم تعديل الجهاز بنجاح!');
    }

    public function destroy(Device $device)
    {
        if ($device->activeSession) {
            return back()->with('error', 'لا يمكن حذف جهاز لديه جلسة نشطة.');
        }
        $device->delete();
        return redirect()->route('admin.devices.index')->with('success', 'تم حذف الجهاز بنجاح!');
    }

    /**
     * Show the form for managing games for a specific device.
     * هذا هو الجزء الذي تم تعديله
     */
    public function manageGames(Device $device)
    {
        $games = Game::all();
        // قمنا بحساب الألعاب المرتبطة هنا
        $assignedGames = $device->games->pluck('id')->toArray();

        // وقمنا بتمرير المتغير إلى الـ view هنا
        return view('admin.devices.manage-games', compact('device', 'games', 'assignedGames'));
    }

    public function syncGames(Request $request, Device $device)
    {
        $request->validate([
            'games' => 'nullable|array',
            'games.*' => 'exists:games,id',
        ]);
        $device->games()->sync($request->input('games', []));
        return redirect()->route('admin.devices.index')->with('success', 'تم تحديث ألعاب الجهاز بنجاح!');
    }
}