<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePessoaRequest extends FormRequest
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
        return [
            'nome' => 'required|min:6',
            'cpf' => 'required|unique:pessoas|min:11|max:11',
            'data_nascimento' => 'required',
            'sexo' => 'required',
            'email' => 'nullable|email',
            'telefone' => 'nullable|min:15|max:15'
        ];
    }

    public function messages (): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.min' => 'Preencha com o seu nome completo',
            'cpf.unique' => 'CPF já cadastrado',
            'cpf.required' => 'O campo CPF é obrigatório',
            'cpf.min' => 'CPF inválido',
            'data_nascimento.required' => 'O campo da data de nascimento é obrigatório',
            'sexo.required' => 'O campo do sexo é obrigatório',
            'email.email' => 'Email inválido',
            'telefone.min' => 'Telefone incompleto'
        ];
    }
}
