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

    public function store(Request $request, $mapId)
    {
        // Validation des données
        $validated = $request->validate([
            'basemap_id' => 'required|string|max:50',
            'is_default' => 'sometimes|boolean'
        ]);

        try {
            // Ajouter map_id aux données validées
            $validated['map_id'] = $mapId;

            // Si on met ce nouveau basemap par défaut, mettre les autres à false
            if (isset($validated['is_default']) && $validated['is_default']) {
                MapBasemap::where('map_id', $mapId)
                    ->update(['is_default' => false]);
            }

            $mapBasemap = MapBasemap::create($validated);

            return new MapBasemapResource($mapBasemap);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du basemap',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    public function update(Request $request, $mapId, $basemapId)
    {
    // Validation des données
    $validated = $request->validate([
        'basemap_id' => 'sometimes|string|max:50',
        'is_default' => 'sometimes|boolean'
    ]);

    // Vérifier que le basemap existe et appartient à la map
    $mapBasemap = MapBasemap::where('map_id', $mapId)
        ->where('id', $basemapId)
        ->firstOrFail();

    try {
        // Si on met ce basemap par défaut, mettre les autres à false
        if (isset($validated['is_default']) && $validated['is_default']) {
            MapBasemap::where('map_id', $mapId)
                ->where('id', '!=', $basemapId)
                ->update(['is_default' => false]);
        }

        $mapBasemap->update($validated);

        return new MapBasemapResource($mapBasemap);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la mise à jour du basemap',
            'errors' => [$e->getMessage()]
        ], 422);
    }
    }

    public function destroy($mapId, $basemapId)
    {
        // Vérifier que le basemap existe et appartient à la map
        $mapBasemap = MapBasemap::where('map_id', $mapId)
            ->where('id', $basemapId)
            ->firstOrFail();

        // Vérifier qu'on ne supprime pas le dernier basemap ou le basemap par défaut
        $basemapCount = MapBasemap::where('map_id', $mapId)->count();
        if ($basemapCount <= 1 || $mapBasemap->is_default) {
            return response()->json([
                'message' => 'Impossible de supprimer le dernier basemap ou le basemap par défaut'
            ], 422);
        }

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