<?php

namespace App\Services;

class NFeClient
{
    /**
     * Envia XML assinado para o Web Service da SEFAZ (simulado)
     *
     * @param string $xmlAssinado
     * @return array
     */
    public function enviarParaSefaz(string $xmlAssinado): array
    {
        // Valida se o XML é válido
        if (!$this->validarXml($xmlAssinado)) {
            return [
                'status' => 'erro',
                'mensagem' => 'XML inválido ou mal formado'
            ];
        }

        // Verifica se o XML possui assinatura digital
        if (!$this->verificarAssinatura($xmlAssinado)) {
            return [
                'status' => 'erro',
                'mensagem' => 'XML não possui assinatura digital válida'
            ];
        }

        // Simula casos específicos de erro (para testes)
        if (strpos($xmlAssinado, 'TIMEOUT_TEST') !== false) {
            return [
                'status' => 'erro',
                'mensagem' => 'Erro de comunicação com a SEFAZ. Timeout na conexão.'
            ];
        }

        // Simula envio bem-sucedido para SEFAZ
        return $this->simularEnvioSucesso($xmlAssinado);
    }

    /**
     * Valida se o XML é bem formado
     *
     * @param string $xml
     * @return bool
     */
    private function validarXml(string $xml): bool
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $result = $dom->loadXML($xml);
        libxml_use_internal_errors(false);
        
        return $result !== false;
    }

    /**
     * Verifica se o XML possui assinatura digital
     *
     * @param string $xml
     * @return bool
     */
    private function verificarAssinatura(string $xml): bool
    {
        // Verifica se contém elementos de assinatura digital
        return strpos($xml, '<ds:Signature') !== false && 
               strpos($xml, 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#"') !== false;
    }

    /**
     * Simula resposta de sucesso da SEFAZ
     *
     * @param string $xmlAssinado
     * @return array
     */
    private function simularEnvioSucesso(string $xmlAssinado): array
    {
        // Gera protocolo único baseado em timestamp e hash do XML
        $protocolo = $this->gerarProtocolo($xmlAssinado);
        
        return [
            'status' => 'sucesso',
            'protocolo' => $protocolo,
            'mensagem' => 'Nota Fiscal autorizada com sucesso',
            'data_autorizacao' => date('Y-m-d H:i:s'),
            'codigo_verificacao' => $this->gerarCodigoVerificacao($protocolo)
        ];
    }

    /**
     * Gera número de protocolo único
     *
     * @param string $xml
     * @return string
     */
    private function gerarProtocolo(string $xml): string
    {
        $timestamp = time();
        $hash = substr(md5($xml . $timestamp), 0, 8);
        
        // Formato típico: 351250000000000 + timestamp + hash
        return '351' . date('Ymd') . sprintf('%04d', rand(1000, 9999)) . $hash;
    }

    /**
     * Gera código de verificação para a nota
     *
     * @param string $protocolo
     * @return string
     */
    private function gerarCodigoVerificacao(string $protocolo): string
    {
        return substr(md5($protocolo . 'SEFAZ_KEY'), 0, 8);
    }
}