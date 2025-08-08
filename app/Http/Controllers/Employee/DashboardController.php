<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the employee dashboard with optimized queries.
     */
    public function index()
    {
        // تم استخدام Eager Loading لتحميل العلاقات بكفاءة عالية في استعلام واحد
        $devices = Device::with(['activeSession.user', 'activeSession.periods.game'])
                         ->latest()
                         ->get();
                         
        return view('employee.dashboard', compact('devices'));
    }
}