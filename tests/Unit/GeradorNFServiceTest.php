<?php

namespace Tests\Unit;

use App\Models\NotaFiscal;
use App\Services\GeradorNFService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeradorNFServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_gerar_xml_de_nota_fiscal()
    {
        $notaFiscal = new NotaFiscal();
        $notaFiscal->numero = 'NF001';
        $notaFiscal->data_emissao = '2025-10-14';
        $notaFiscal->tipo = 'saida';
        $notaFiscal->valor_total = 1500.50;

        $geradorService = new GeradorNFService();
        $xml = $geradorService->gerarXml($notaFiscal);

        // Verifica se é uma string válida
        $this->assertIsString($xml);
        
        // Verifica se contém as tags principais
        $this->assertStringContainsString('<notaFiscal>', $xml);
        $this->assertStringContainsString('</notaFiscal>', $xml);
        $this->assertStringContainsString('<numero>NF001</numero>', $xml);
        $this->assertStringContainsString('<dataEmissao>2025-10-14</dataEmissao>', $xml);
        $this->assertStringContainsString('<tipo>saida</tipo>', $xml);
        $this->assertStringContainsString('<valorTotal>1500.50</valorTotal>', $xml);
        
        // Verifica se é um XML válido
        $dom = new \DOMDocument();
        $result = $dom->loadXML($xml);
        $this->assertTrue($result, 'XML deve ser válido');
    }

    /** @test */
    public function xml_gerado_contem_estrutura_basica()
    {
        $notaFiscal = new NotaFiscal();
        $notaFiscal->numero = 'NF002';
        $notaFiscal->data_emissao = '2025-10-15';
        $notaFiscal->tipo = 'entrada';
        $notaFiscal->valor_total = 750.25;

        $geradorService = new GeradorNFService();
        $xml = $geradorService->gerarXml($notaFiscal);

        // Carrega XML para validar estrutura
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        
        $xpath = new \DOMXPath($dom);
        
        // Verifica se existem os nós necessários
        $this->assertEquals(1, $xpath->query('//notaFiscal')->length);
        $this->assertEquals(1, $xpath->query('//numero')->length);
        $this->assertEquals(1, $xpath->query('//dataEmissao')->length);
        $this->assertEquals(1, $xpath->query('//tipo')->length);
        $this->assertEquals(1, $xpath->query('//valorTotal')->length);
        
        // Verifica valores
        $this->assertEquals('NF002', $xpath->query('//numero')->item(0)->nodeValue);
        $this->assertEquals('2025-10-15', $xpath->query('//dataEmissao')->item(0)->nodeValue);
        $this->assertEquals('entrada', $xpath->query('//tipo')->item(0)->nodeValue);
        $this->assertEquals('750.25', $xpath->query('//valorTotal')->item(0)->nodeValue);
    }
}