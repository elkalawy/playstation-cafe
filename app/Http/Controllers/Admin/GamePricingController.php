<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePricing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GamePricingController extends Controller
{
    /**
     * Display a listing of the resource.
     * هذا هو الجزء الذي تم تعديله
     */
    public function index(Game $game)
    {
        $pricings = $game->pricings()->orderBy('controller_count')->get();

        // قمنا بتمرير المتغير '$game' إلى الـ view هنا
        return view('admin.games.pricings.index', compact('game', 'pricings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Game $game)
    {
        $validated = $request->validate([
            'controller_count' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('game_pricings')->where(function ($query) use ($game) {
                    return $query->where('game_id', $game->id);
                }),
            ],
            'price' => 'required|numeric|min:0',
            'duration_in_minutes' => 'required|integer|min:1',
        ], [
            'controller_count.unique' => 'يوجد سعر مسجل بالفعل لهذا العدد من وحدات التحكم.',
        ]);

        $game->pricings()->create($validated);

        return redirect()->route('admin.games.pricings.index', $game)->with('success', 'تمت إضافة السعر بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamePricing $pricing)
    {
        $gameId = $pricing->game_id;
        $pricing->delete();
        return redirect()->route('admin.games.pricings.index', ['game' => $gameId])
                         ->with('success', 'تم حذف السعر بنجاح!');
    }
}