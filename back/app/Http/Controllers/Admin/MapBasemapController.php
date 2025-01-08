<?php

namespace App\Http\Controllers\Admin;

use App\Models\Map;
use App\Models\MapBasemap;
use Illuminate\Http\Request;
use App\Http\Resources\MapBasemapResource;

    class MapBasemapController extends BaseAdminController
    {
        public function index(Map $map)
    {
        $mapBasemaps = MapBasemap::where('map_id', $map->id)->get();
        return MapBasemapResource::collection($mapBasemaps);
    }

    public function show(MapBasemap $mapBasemap)
    {
        return new MapBasemapResource($mapBasemap);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'map_id' => 'required|integer',
            'basemap_id' => 'required|string|max:50',
            'is_default' => 'boolean'
        ]);

        try {
            $mapBasemap = MapBasemap::create($validated);
            return new MapBasemapResource($mapBasemap);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du basemap',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    public function update(Request $request, MapBasemap $mapBasemap)
    {
        $validated = $request->validate([
            'map_id' => 'sometimes|integer',
            'basemap_id' => 'sometimes|string|max:50',
            'is_default' => 'sometimes|boolean'
        ]);

        try {
            $mapBasemap->update($validated);
            return new MapBasemapResource($mapBasemap);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du basemap',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    public function destroy(MapBasemap $mapBasemap)
    {
        try {
            $mapBasemap->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du basemap',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }
}