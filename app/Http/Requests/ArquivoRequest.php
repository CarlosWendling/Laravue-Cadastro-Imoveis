<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArquivoRequest extends FormRequest
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
            'files.*' => ['file', 'max:3072', 'mimes:jpg,jpeg,png,pdf'],
            'files' => ['nullable', 'array', 'max:5'],
            'inscricao_municipal_imovel' => ['required', 'string'],
        ];
    }
}
