<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::user()->admin === true;
    }

    public function rules()
    {
        // Récupérer l'id de l'utilisateur depuis la route
        $userId = $this->route('user')->id;
        
        return [
            'username' => 'sometimes|required|string|unique:users,username,' . $userId,
            'email' => 'sometimes|required|email',
            'password' => 'nullable|min:8',
            'admin' => 'sometimes|boolean',
            'premium' => 'sometimes|boolean',
            'background_picture' => 'nullable|string'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Les données fournies sont invalides',
            'errors' => $validator->errors()
        ], 422));
    }
}