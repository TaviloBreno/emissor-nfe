<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManifestacaoRequest extends FormRequest
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
            'tipo_manifestacao' => 'required|in:ciencia,confirmacao,discordancia',
            'justificativa' => 'required|string|min:15|max:255'
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
            'tipo_manifestacao.required' => 'O tipo de manifestação é obrigatório',
            'tipo_manifestacao.in' => 'Tipo de manifestação deve ser: ciencia, confirmacao ou discordancia',
            'justificativa.required' => 'A justificativa é obrigatória',
            'justificativa.min' => 'A justificativa deve ter pelo menos 15 caracteres',
            'justificativa.max' => 'A justificativa não pode ter mais que 255 caracteres'
        ];
    }
}