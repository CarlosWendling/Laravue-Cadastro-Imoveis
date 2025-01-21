<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAverbacaoRequest extends FormRequest
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
            'evento' => 'required',
            'descricao' => 'required|min:10',
            'data_averbacao' => 'required',
            'inscricao_municipal_imovel' => 'required',
        ];

        if ($this->input('evento') == 'Aumento Área Construída' || $this->input('evento') == 'Redução Área Construída') {
            $rules['medida'] = ['required', 'numeric', 'min:1'];
        } else {
            $rules['medida'] = ['nullable'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'evento.required' => 'O campo do evento é obrigatório',
            'descricao.required' => 'O campo da descrição é obrigatório',
            'descricao.min' => 'O campo da descrição deve ter no mínimo 10 caracteres',
            'medida.required' => 'O campo da medida é obrigatório',
            'medida.min' => 'O campo da medida não pode ser zero',
        ];
    }
}
