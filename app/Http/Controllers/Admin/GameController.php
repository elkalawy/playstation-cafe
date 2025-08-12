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
        $games = Game::latest()->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'is_game_based_playable' => 'required|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('games_covers', 'public');
        }

        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'تمت إضافة اللعبة بنجاح.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'is_game_based_playable' => 'required|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($game->getRawOriginal('cover_image')) {
                Storage::disk('public')->delete($game->getRawOriginal('cover_image'));
            }
            $validated['cover_image'] = $request->file('cover_image')->store('games_covers', 'public');
        }

        $game->update($validated);

        return redirect()->route('admin.games.index')->with('success', 'تم تحديث اللعبة بنجاح.');
    }

    public function destroy(Game $game)
    {
        if ($game->getRawOriginal('cover_image')) {
            Storage::disk('public')->delete($game->getRawOriginal('cover_image'));
        }
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'تم حذف اللعبة بنجاح.');
    }
}