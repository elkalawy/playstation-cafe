<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DevicePricing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DevicePricingController extends Controller
{
    public function index(Device $device)
    {
        $pricings = $device->pricings()->orderBy('controller_count')->get();
        return view('admin.devices.pricings.index', compact('device', 'pricings'));
    }

    public function store(Request $request, Device $device)
    {
        $validated = $request->validate([
            'controller_count' => [
                'required', 'integer', 'min:1',
                Rule::unique('device_pricings')->where('device_id', $device->id),
            ],
            'price_per_hour' => 'required|numeric|min:0',
        ], ['controller_count.unique' => 'يوجد سعر مسجل بالفعل لهذا العدد.']);

        $device->pricings()->create($validated);
        return redirect()->route('admin.devices.pricings.index', $device)->with('success', 'تمت إضافة السعر بنجاح!');
    }

    public function destroy(DevicePricing $pricing)
    {
        // نحصل على الجهاز قبل حذف السعر لضمان إعادة التوجيه الصحيحة
        $device = $pricing->device;
        $pricing->delete();
        
        return redirect()->route('admin.devices.pricings.index', $device)->with('success', 'تم حذف السعر بنجاح!');
    }
}