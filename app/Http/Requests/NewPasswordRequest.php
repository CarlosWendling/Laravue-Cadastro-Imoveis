<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class NewPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'current_password' => ['required', 'string'],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Preencha com a senha atual',
            'password.min' => 'A senha precisa ter no mínimo 8 caracteres',
            'password.required' => 'Preencha com a nova senha',
            'password.confirmed' => 'A senha nova não coincide'
        ];
    }
}
