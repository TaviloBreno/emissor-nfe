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
            'numero' => [
                'required',
                'string',
                'min:4',
                'max:20',
                'unique:nota_fiscals,numero'
            ],
            'data_emissao' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:today'
            ],
            'tipo' => [
                'required',
                'in:entrada,saida'
            ],
            'valor_total' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999.99'
            ],
            'protocolo_autorizacao' => [
                'sometimes',
                'nullable',
                'string',
                'max:255'
            ],
            'xml_nfe' => [
                'sometimes',
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidXml($value)) {
                        $fail('O campo XML NFe deve conter um XML válido.');
                    }
                }
            ]
        ];
    }

    /**
     * Verifica se o conteúdo é um XML válido
     */
    private function isValidXml(string $xml): bool
    {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml);
        
        if ($doc === false) {
            return false;
        }
        
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'numero.required' => 'O número da nota é obrigatório.',
            'numero.string' => 'O número da nota deve ser um texto.',
            'numero.min' => 'O número da nota deve ter pelo menos 4 caracteres.',
            'numero.max' => 'O número da nota deve ter no máximo 20 caracteres.',
            'numero.unique' => 'Este número de nota já existe.',
            
            'data_emissao.required' => 'A data de emissão é obrigatória.',
            'data_emissao.date' => 'A data de emissão deve ser uma data válida.',
            'data_emissao.date_format' => 'A data de emissão deve estar no formato AAAA-MM-DD.',
            'data_emissao.before_or_equal' => 'A data de emissão não pode ser futura.',
            
            'tipo.required' => 'O tipo da nota é obrigatório.',
            'tipo.in' => 'O tipo deve ser "entrada" ou "saida".',
            
            'valor_total.required' => 'O valor total é obrigatório.',
            'valor_total.numeric' => 'O valor total deve ser um número.',
            'valor_total.min' => 'O valor total deve ser maior que zero.',
            'valor_total.max' => 'O valor total deve ser menor que R$ 999.999.999,99.',
            
            'protocolo_autorizacao.string' => 'O protocolo deve ser um texto.',
            'protocolo_autorizacao.max' => 'O protocolo deve ter no máximo 255 caracteres.',
            
            'xml_nfe.string' => 'O XML deve ser um texto válido.',
        ];
    }
}