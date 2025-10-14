<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InutilizacaoRequest extends FormRequest
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
            'serie' => 'nullable|string|size:3',
            'numero_inicial' => 'required|string|max:9',
            'numero_final' => 'required|string|max:9|gte:numero_inicial',
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
            'numero_inicial.required' => 'O número inicial é obrigatório.',
            'numero_final.required' => 'O número final é obrigatório.',
            'numero_final.gte' => 'O número final deve ser maior ou igual ao número inicial.',
            'justificativa.required' => 'A justificativa é obrigatória.',
            'justificativa.min' => 'A justificativa deve ter pelo menos 15 caracteres.',
            'justificativa.max' => 'A justificativa não pode exceder 255 caracteres.',
            'serie.size' => 'A série deve ter exatamente 3 caracteres.'
        ];
    }

    /**
     * Prepara os dados para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Se não informar série, usa '001' como padrão
        if (!$this->has('serie')) {
            $this->merge(['serie' => '001']);
        }
    }
}
