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
        return Auth::user()->admin;
    }

    public function rules()
    {
        return [
            'username' => 'sometimes|required|string|unique:users,username,' . $this->user->id,
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

    public function messages()
    {
        return [
            'username.required' => 'Le nom d\'utilisateur est requis',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé',
            'email.required' => 'L\'email est requis',
            'email.email' => 'L\'email doit être une adresse valide',
            'password.min' => 'Le mot de passe doit faire au moins 8 caractères',
        ];
    }
}