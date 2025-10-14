<?php

namespace Tests\Unit;

use App\Services\AssinadorService;
use Tests\TestCase;

class AssinadorServiceTest extends TestCase
{
    /** @test */
    public function pode_adicionar_assinatura_digital_ao_xml()
    {
        $xmlSimples = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal>
    <numero>NF001</numero>
    <dataEmissao>2025-10-14</dataEmissao>
    <tipo>saida</tipo>
    <valorTotal>1500.50</valorTotal>
</notaFiscal>';

        $assinadorService = new AssinadorService();
        $xmlAssinado = $assinadorService->assinarXml($xmlSimples);

        // Verifica se o XML ainda é válido
        $dom = new \DOMDocument();
        $result = $dom->loadXML($xmlAssinado);
        $this->assertTrue($result, 'XML assinado deve ser válido');

        // Verifica se contém elementos de assinatura digital
        $this->assertStringContainsString('<ds:Signature', $xmlAssinado);
        $this->assertStringContainsString('xmlns:ds="http://www.w3.org/2000/09/xmldsig#"', $xmlAssinado);
        $this->assertStringContainsString('<ds:SignedInfo>', $xmlAssinado);
        $this->assertStringContainsString('<ds:SignatureValue>', $xmlAssinado);
        $this->assertStringContainsString('<ds:KeyInfo>', $xmlAssinado);

        // Verifica se o conteúdo original ainda está presente
        $this->assertStringContainsString('<numero>NF001</numero>', $xmlAssinado);
        $this->assertStringContainsString('<valorTotal>1500.50</valorTotal>', $xmlAssinado);
    }

    /** @test */
    public function xml_assinado_contem_estrutura_signature_valida()
    {
        $xmlSimples = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal>
    <numero>NF002</numero>
    <dataEmissao>2025-10-15</dataEmissao>
    <tipo>entrada</tipo>
    <valorTotal>750.25</valorTotal>
</notaFiscal>';

        $assinadorService = new AssinadorService();
        $xmlAssinado = $assinadorService->assinarXml($xmlSimples);

        // Carrega XML para validar estrutura de assinatura
        $dom = new \DOMDocument();
        $dom->loadXML($xmlAssinado);
        
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
        
        // Verifica se existem os elementos necessários da assinatura
        $this->assertEquals(1, $xpath->query('//ds:Signature')->length, 'Deve ter um elemento Signature');
        $this->assertEquals(1, $xpath->query('//ds:SignedInfo')->length, 'Deve ter um elemento SignedInfo');
        $this->assertEquals(1, $xpath->query('//ds:SignatureValue')->length, 'Deve ter um elemento SignatureValue');
        $this->assertEquals(1, $xpath->query('//ds:KeyInfo')->length, 'Deve ter um elemento KeyInfo');
        
        // Verifica se SignatureValue não está vazio
        $signatureValue = $xpath->query('//ds:SignatureValue')->item(0)->nodeValue;
        $this->assertNotEmpty(trim($signatureValue), 'SignatureValue não deve estar vazio');
    }

    /** @test */
    public function preserva_conteudo_original_apos_assinatura()
    {
        $xmlOriginal = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal id="NF003">
    <numero>NF003</numero>
    <dataEmissao>2025-10-16</dataEmissao>
    <tipo>saida</tipo>
    <valorTotal>999.99</valorTotal>
    <items>
        <item>Produto A</item>
        <item>Produto B</item>
    </items>
</notaFiscal>';

        $assinadorService = new AssinadorService();
        $xmlAssinado = $assinadorService->assinarXml($xmlOriginal);

        // Verifica se todo o conteúdo original foi preservado
        $this->assertStringContainsString('id="NF003"', $xmlAssinado);
        $this->assertStringContainsString('<numero>NF003</numero>', $xmlAssinado);
        $this->assertStringContainsString('<valorTotal>999.99</valorTotal>', $xmlAssinado);
        $this->assertStringContainsString('<item>Produto A</item>', $xmlAssinado);
        $this->assertStringContainsString('<item>Produto B</item>', $xmlAssinado);
    }
}