<?php

namespace App\Http\Requests;

use App\Models\Pessoa;
use App\Rules\ValidacaoCpf;
use App\Rules\ValidacaoIdade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $rules = [
            'nome' => 'required|min:6',
            'data_nascimento' => ['required', new ValidacaoIdade()],
            'sexo' => 'required',
            'cpf' => ['required', Rule::unique(Pessoa::class)->ignore($this->id)], //new ValidacaoCpf()
            'email' => ['nullable', 'email', Rule::unique(Pessoa::class)->ignore($this->id)],
            'telefone' => 'nullable|min:11|max:11'
        ];

        return $rules;
    }

    public function messages (): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.min' => 'Preencha com o seu nome completo',
            'cpf.unique' => 'CPF já cadastrado',
            'cpf.required' => 'O campo CPF é obrigatório',
            'data_nascimento.required' => 'O campo da data de nascimento é obrigatório',
            'sexo.required' => 'O campo do sexo é obrigatório',
            'email.email' => 'Email inválido',
            'email.unique' => 'Email já cadastrado',
            'telefone.min' => 'Telefone incompleto'
        ];
    }
}
