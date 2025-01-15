<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserBackgroundController extends Controller
{
    public function upload(Request $request, $userId)
    {
        $request->validate([
            'background_picture' => 'required|image|mimes:jpg,gif,png,jpeg|max:2048'
        ]);

        try {
            $user = User::findOrFail($userId);

            if ($request->hasFile('background_picture')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->background_picture && Storage::exists('uploads/' . $user->background_picture)) {
                    Storage::delete('uploads/' . $user->background_picture);
                }

                // Générer un nom unique et sauvegarder la nouvelle image
                $fileName = uniqid() . '.' . $request->background_picture->extension();
                $request->background_picture->storeAs('uploads', $fileName);

                // Mettre à jour la base de données
                $user->update([
                    'background_picture' => $fileName
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Image uploadée avec succès',
                    'file_name' => $fileName
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'upload : ' . $e->getMessage()
            ], 422);
        }
    }
}