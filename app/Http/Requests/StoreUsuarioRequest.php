<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Rules\ValidacaoCpf;

class StoreUsuarioRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:6',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->id)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['required', Rule::unique(User::class)->ignore($this->id), new ValidacaoCpf()],
            'perfil' => 'required',
            'ativo' => 'required',
        ];

        return $rules;
    }

    public function messages (): array
    {
        return [
            'name.required' => 'O campo do nome é obrigatório',
            'name.min' => 'Preencha com o nome completo',
            'email.required' => 'O campo do email é obrigatório',
            'email.email' => 'Email inválido',
            'email.unique' => 'Email já cadastrado',
            'password.required' => 'O campo da senha é obrigatório',
            'password.confirmed' => 'As senhas não coincidem',
            'cpf.required' => 'O campo do CPF é obrigatório',
            'cpf.unique' => 'CPF já cadastrado',
            'perfil.required' => 'O campo do perfil é obrigatório',
        ];
    }
}
