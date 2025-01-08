<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MapBasemapResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'map_id' => $this->map_id,
            'basemap_id' => $this->basemap_id,
            'is_default' => (bool)$this->is_default,
        ];
    }
}