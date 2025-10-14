<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartaCorrecaoRequest extends FormRequest
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
            'campo_corrigido' => 'required|string|in:' . implode(',', $this->getCamposPermitidos()),
            'valor_anterior' => 'required|string|max:500',
            'valor_novo' => 'required|string|max:500|different:valor_anterior',
            'justificativa' => 'required|string|min:15|max:255'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'campo_corrigido.required' => 'O campo a ser corrigido é obrigatório.',
            'campo_corrigido.in' => 'O campo informado não pode ser corrigido via carta de correção.',
            'valor_anterior.required' => 'O valor anterior é obrigatório.',
            'valor_novo.required' => 'O valor novo é obrigatório.',
            'valor_novo.different' => 'O valor novo deve ser diferente do valor anterior.',
            'justificativa.required' => 'A justificativa é obrigatória.',
            'justificativa.min' => 'A justificativa deve ter pelo menos 15 caracteres.',
            'justificativa.max' => 'A justificativa não pode exceder 255 caracteres.'
        ];
    }

    /**
     * Campos permitidos para correção
     *
     * @return array
     */
    private function getCamposPermitidos(): array
    {
        return [
            'endereco_entrega',
            'dados_adicionais',
            'informacoes_complementares',
            'endereco_retirada',
            'dados_transportador',
            'observacoes'
        ];
    }
}
