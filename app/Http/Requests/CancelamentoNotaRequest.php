<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelamentoNotaRequest extends FormRequest
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
            'justificativa.required' => 'A justificativa é obrigatória para cancelamento.',
            'justificativa.min' => 'A justificativa deve ter pelo menos 15 caracteres.',
            'justificativa.max' => 'A justificativa não pode exceder 255 caracteres.'
        ];
    }
}
