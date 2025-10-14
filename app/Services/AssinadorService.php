<?php

namespace App\Services;

class AssinadorService
{
    /**
     * Assina um XML com assinatura digital simulada
     *
     * @param string $xml
     * @return string
     */
    public function assinarXml(string $xml): string
    {
        // Carrega o XML original
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);
        $dom->formatOutput = true;

        // Obtém o elemento raiz
        $rootElement = $dom->documentElement;

        // Cria a estrutura de assinatura digital (simulada)
        $signature = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'ds:Signature');
        
        // SignedInfo
        $signedInfo = $dom->createElement('ds:SignedInfo');
        $canonicalizationMethod = $dom->createElement('ds:CanonicalizationMethod');
        $canonicalizationMethod->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $signedInfo->appendChild($canonicalizationMethod);
        
        $signatureMethod = $dom->createElement('ds:SignatureMethod');
        $signatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
        $signedInfo->appendChild($signatureMethod);
        
        $reference = $dom->createElement('ds:Reference');
        $reference->setAttribute('URI', '');
        $transforms = $dom->createElement('ds:Transforms');
        $transform = $dom->createElement('ds:Transform');
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $transforms->appendChild($transform);
        $reference->appendChild($transforms);
        
        $digestMethod = $dom->createElement('ds:DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $reference->appendChild($digestMethod);
        
        $digestValue = $dom->createElement('ds:DigestValue', base64_encode(hash('sha1', $dom->saveXML(), true)));
        $reference->appendChild($digestValue);
        $signedInfo->appendChild($reference);
        
        $signature->appendChild($signedInfo);

        // SignatureValue (simulado)
        $signatureValue = $dom->createElement('ds:SignatureValue', $this->gerarAssinaturaSimulada($dom->saveXML()));
        $signature->appendChild($signatureValue);

        // KeyInfo
        $keyInfo = $dom->createElement('ds:KeyInfo');
        $x509Data = $dom->createElement('ds:X509Data');
        $x509Certificate = $dom->createElement('ds:X509Certificate', $this->gerarCertificadoSimulado());
        $x509Data->appendChild($x509Certificate);
        $keyInfo->appendChild($x509Data);
        $signature->appendChild($keyInfo);

        // Adiciona a assinatura ao elemento raiz
        $rootElement->appendChild($signature);

        return $dom->saveXML();
    }

    /**
     * Gera uma assinatura simulada (base64)
     *
     * @param string $content
     * @return string
     */
    private function gerarAssinaturaSimulada(string $content): string
    {
        // Simulação de assinatura RSA
        $hash = hash('sha256', $content);
        return base64_encode('SIMULATED_RSA_SIGNATURE_' . $hash . '_' . time());
    }

    /**
     * Gera um certificado X509 simulado (base64)
     *
     * @return string
     */
    private function gerarCertificadoSimulado(): string
    {
        // Simulação de certificado X509
        $certData = 'SIMULATED_X509_CERTIFICATE_' . time();
        return base64_encode($certData);
    }
}