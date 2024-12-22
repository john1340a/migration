<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'admin' => (bool)$this->admin,
            'premium' => (bool)$this->premium,
            'background_picture' => $this->background_picture,
            'maps' => MapResource::collection($this->whenLoaded('maps')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}