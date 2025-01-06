<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImovelRequest extends FormRequest
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
            'logradouro' => 'required|min:6',
            'bairro' => 'required|min:4',
            'complemento' => 'nullable',
            'numero' => 'required',
            'contribuinte' => 'required',
            'area_terreno' => 'nullable',
            'area_edificacao' => 'nullable',
            'tipo' => 'required',
            'situacao' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'logradouro.required' => 'O campo do endereço é obrigatório',
            'logradouro.min' => 'Preencha com um endereço válido',
            'bairro.required' => 'O campo do bairro é obrigatório',
            'bairro.min' => 'Preencha com um bairro válido',
            'numero.required' => 'O campo do numero é obrigatório',
            'contribuinte.required' => 'Selecione um contribuinte',
            'tipo.required' => 'Selecione o tipo da propriedade'
        ];
    }
}
