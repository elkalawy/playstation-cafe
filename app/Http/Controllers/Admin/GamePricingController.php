<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePricing;
use Illuminate\Http\Request;

class GamePricingController extends Controller
{
    public function index(Game $game)
    {
        // تم تحميل العلاقة بشكل مسبق لتحسين الأداء
        $game->load('pricings');
        return view('admin.games.pricings.index', compact('game'));
    }

    public function store(Request $request, Game $game)
    {
        $validated = $request->validate([
            'controller_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'duration_in_minutes' => 'required|integer|min:1',
        ]);

        $game->pricings()->create($validated);

        return back()->with('success', 'تمت إضافة السعر بنجاح.');
    }

    public function destroy(GamePricing $pricing)
    {
        $pricing->delete();
        return back()->with('success', 'تم حذف السعر بنجاح.');
    }
}