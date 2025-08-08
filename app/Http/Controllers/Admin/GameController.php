<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::latest()->get();
        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     * هذا هو الجزء الذي تم تعديله
     */
    public function create()
    {
        // قمنا بإنشاء متغير لعبة جديد وفارغ هنا
        $game = new Game();
        // وقمنا بتمريره إلى الـ view
        return view('admin.games.create', compact('game'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_game_based_playable' => 'required|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        Game::create($validated);
        return redirect()->route('admin.games.index')->with('success', 'تمت إضافة اللعبة بنجاح!');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_game_based_playable' => 'required|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($game->cover_image) {
                Storage::disk('public')->delete($game->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $game->update($validated);
        return redirect()->route('admin.games.index')->with('success', 'تم تعديل اللعبة بنجاح!');
    }

    public function destroy(Game $game)
    {
        if ($game->cover_image) {
            Storage::disk('public')->delete($game->cover_image);
        }
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'تم حذف اللعبة بنجاح!');
    }
}