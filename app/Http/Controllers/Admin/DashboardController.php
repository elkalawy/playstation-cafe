<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Game;
use App\Models\PlaySession;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'devices_count' => Device::count(),
            'games_count' => Game::count(),
            'active_sessions_count' => PlaySession::where('status', 'active')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}