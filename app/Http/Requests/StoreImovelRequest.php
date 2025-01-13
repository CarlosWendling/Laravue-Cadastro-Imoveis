<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'tipo' => 'required',
            'files.*' => ['file', 'max:3072', 'mimes:jpg,jpeg,png,pdf'],
            'files' => ['nullable', 'array', 'max:5'],
            'situacao' => 'required'
        ];

        if ($this->input('tipo') == 'Terreno') {
            $rules['area_terreno'] = 'required|numeric|min:1';
            $rules['area_edificacao'] = 'required|numeric|min:0';
        }

        if ($this->input('tipo') == 'Casa') {
            $rules['area_terreno'] = 'required|numeric|min:1';
            $rules['area_edificacao'] = 'required|numeric|min:1';
        }

        if ($this->input('tipo') == 'Apartamento') {
            $rules['area_terreno'] = 'required|numeric|min:0';
            $rules['area_edificacao'] = 'required|numeric|min:1';
        }

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
            'area_terreno.required' => 'A área do terreno é obrigatória',
            'area_terreno.min' => 'A área do terreno não pode ser zero',
            'area_edificacao.required' => 'A área da edificação é obrigatória',
            'area_edificacao.min' => 'A área da edificação não pode ser zero',
            'tipo.required' => 'Selecione o tipo da propriedade'
        ];
    }
}
