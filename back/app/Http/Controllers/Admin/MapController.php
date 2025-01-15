<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\User;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(User $user)
    {
        // Assuming maps are associated via fk_user_id
        return Map::where('fk_user_id', $user->id)->get();
    }

    public function adminIndex()
    {
        return Map::distinct('title')->get();
    }

    public function show(User $user, Map $map)
    {
        // Check if the map belongs to the user
        if ($map->fk_user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $map;
    }

    public function adminStore(Request $request, User $user)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $map = $user->maps()->create([
            'title' => $validated['title']
        ]);

        return response()->json($map, 201);
    }

    public function adminUpdate(Request $request, User $user, Map $map)
    {
        // Ensure the map belongs to the user
        if ($map->fk_user_id !== $user->id) {
            return response()->json(['message' => 'Map not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $map->update($validated);

        return response()->json($map);
    }

    public function adminDestroy(User $user, Map $map)
    {
        // Ensure the map belongs to the user
        if ($map->fk_user_id !== $user->id) {
            return response()->json(['message' => 'Map not found'], 404);
        }

        $map->delete();

        return response()->json(null, 204);
    }
}