<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotaFiscalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'numero' => 'required|string|max:255|unique:nota_fiscals,numero',
            'data_emissao' => 'required|date',
            'tipo' => 'required|in:entrada,saida',
            'valor_total' => 'required|numeric|min:0'
        ];
    }
}
