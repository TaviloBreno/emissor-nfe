<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotaFiscalWebRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'numero' => 'required|string|unique:nota_fiscals,numero|max:20',
            'data_emissao' => 'required|date',
            'tipo' => 'required|in:entrada,saida',
            'valor_total' => 'required|numeric|min:0.01|max:999999.99'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'numero.required' => 'O número da nota fiscal é obrigatório',
            'numero.unique' => 'Este número de nota fiscal já existe',
            'numero.max' => 'O número da nota fiscal não pode ter mais que 20 caracteres',
            'data_emissao.required' => 'A data de emissão é obrigatória',
            'data_emissao.date' => 'A data de emissão deve ser uma data válida',
            'tipo.required' => 'O tipo da nota fiscal é obrigatório',
            'tipo.in' => 'O tipo deve ser entrada ou saída',
            'valor_total.required' => 'O valor total é obrigatório',
            'valor_total.numeric' => 'O valor total deve ser um número',
            'valor_total.min' => 'O valor total deve ser maior que zero',
            'valor_total.max' => 'O valor total não pode ser maior que R$ 999.999,99'
        ];
    }
}