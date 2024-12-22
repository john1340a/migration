<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Map;
use App\Http\Requests\Admin\AddMapRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseAdminController
{
    public function index()
    {
        $users = User::paginate(15);
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user->load('maps'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['admin'] = $request->boolean('admin');
        $data['premium'] = $request->boolean('premium') || $data['admin'];

        try {
            $user->update($data);
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ], 403);
        }

        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    public function addMap(AddMapRequest $request, User $user)
    {
        try {
            $map = $user->maps()->create($request->validated());
            return response()->json($map, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout de la carte',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }
}

// app/Http/Resources/UserResource.php
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