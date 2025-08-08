<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeviceController as AdminDeviceController;
use App\Http\Controllers\Admin\DevicePricingController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\GamePricingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\PlaySessionController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (Auth::check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('employee.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->only(['create', 'store']);
    Route::resource('devices', AdminDeviceController::class)->except(['show']);
    Route::resource('games', AdminGameController::class)->except(['show']);

    Route::get('devices/{device}/manage-games', [AdminDeviceController::class, 'manageGames'])->name('devices.manageGames');
    Route::post('devices/{device}/sync-games', [AdminDeviceController::class, 'syncGames'])->name('devices.syncGames');

    Route::get('devices/{device}/pricings', [DevicePricingController::class, 'index'])->name('devices.pricings.index');
    Route::post('devices/{device}/pricings', [DevicePricingController::class, 'store'])->name('devices.pricings.store');
    // --- تم توحيد هذا المسار ---
    Route::delete('device-pricings/{pricing}', [DevicePricingController::class, 'destroy'])->name('devices.pricings.destroy');

    Route::get('games/{game}/pricings', [GamePricingController::class, 'index'])->name('games.pricings.index');
    Route::post('games/{game}/pricings', [GamePricingController::class, 'store'])->name('games.pricings.store');
    // --- وتم توحيد هذا المسار ---
    Route::delete('game-pricings/{pricing}', [GamePricingController::class, 'destroy'])->name('games.pricings.destroy');
});

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/play-sessions/start', [PlaySessionController::class, 'start'])->name('play_sessions.start');
    Route::post('/play-sessions/{playSession}/end', [PlaySessionController::class, 'end'])->name('play_sessions.end');
    Route::post('/play-sessions/{playSession}/switch', [PlaySessionController::class, 'switchPlayType'])->name('play_sessions.switch');
});

require __DIR__.'/auth.php';