<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class AddMapRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::user()->admin === true;
    }

    public function rules()
    {
        return [
            'new_map_name' => 'required|string|max:255'
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
            'new_map_name.required' => 'Le nom de la carte est requis',
            'new_map_name.max' => 'Le nom de la carte ne doit pas dépasser 255 caractères'
        ];
    }
}