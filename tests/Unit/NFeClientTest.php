<?php

namespace Tests\Unit;

use App\Services\NFeClient;
use Tests\TestCase;
use Mockery;

class NFeClientTest extends TestCase
{
    /** @test */
    public function pode_enviar_xml_assinado_para_sefaz_e_receber_protocolo()
    {
        $xmlAssinado = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
    <numero>NF001</numero>
    <dataEmissao>2025-10-14</dataEmissao>
    <tipo>saida</tipo>
    <valorTotal>1500.50</valorTotal>
    <ds:Signature>
        <ds:SignedInfo>
            <ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
            <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
        </ds:SignedInfo>
        <ds:SignatureValue>SIMULATED_SIGNATURE</ds:SignatureValue>
        <ds:KeyInfo>
            <ds:X509Data>
                <ds:X509Certificate>SIMULATED_CERT</ds:X509Certificate>
            </ds:X509Data>
        </ds:KeyInfo>
    </ds:Signature>
</notaFiscal>';

        $nfeClient = new NFeClient();
        $resposta = $nfeClient->enviarParaSefaz($xmlAssinado);

        // Verifica se a resposta contém protocolo de sucesso
        $this->assertIsArray($resposta);
        $this->assertArrayHasKey('status', $resposta);
        $this->assertArrayHasKey('protocolo', $resposta);
        $this->assertArrayHasKey('mensagem', $resposta);
        
        $this->assertEquals('sucesso', $resposta['status']);
        $this->assertNotEmpty($resposta['protocolo']);
        $this->assertEquals('Nota Fiscal autorizada com sucesso', $resposta['mensagem']);
    }

    /** @test */
    public function gera_protocolo_unico_para_cada_envio()
    {
        $xmlAssinado1 = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal><numero>NF001</numero><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#"></ds:Signature></notaFiscal>';

        $xmlAssinado2 = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal><numero>NF002</numero><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#"></ds:Signature></notaFiscal>';

        $nfeClient = new NFeClient();
        $resposta1 = $nfeClient->enviarParaSefaz($xmlAssinado1);
        $resposta2 = $nfeClient->enviarParaSefaz($xmlAssinado2);

        // Protocolos devem ser diferentes
        $this->assertNotEquals($resposta1['protocolo'], $resposta2['protocolo']);
        $this->assertEquals('sucesso', $resposta1['status']);
        $this->assertEquals('sucesso', $resposta2['status']);
    }

    /** @test */
    public function retorna_erro_para_xml_nao_assinado()
    {
        $xmlNaoAssinado = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal>
    <numero>NF003</numero>
    <dataEmissao>2025-10-14</dataEmissao>
    <tipo>saida</tipo>
    <valorTotal>1500.50</valorTotal>
</notaFiscal>';

        $nfeClient = new NFeClient();
        $resposta = $nfeClient->enviarParaSefaz($xmlNaoAssinado);

        // Verifica se retorna erro para XML não assinado
        $this->assertIsArray($resposta);
        $this->assertEquals('erro', $resposta['status']);
        $this->assertArrayHasKey('mensagem', $resposta);
        $this->assertStringContainsString('assinatura', strtolower($resposta['mensagem']));
        $this->assertArrayNotHasKey('protocolo', $resposta);
    }

    /** @test */
    public function valida_estrutura_xml_antes_do_envio()
    {
        $xmlInvalido = 'XML inválido sem estrutura';

        $nfeClient = new NFeClient();
        $resposta = $nfeClient->enviarParaSefaz($xmlInvalido);

        // Verifica se retorna erro para XML inválido
        $this->assertIsArray($resposta);
        $this->assertEquals('erro', $resposta['status']);
        $this->assertArrayHasKey('mensagem', $resposta);
        $this->assertStringContainsString('XML', $resposta['mensagem']);
        $this->assertArrayNotHasKey('protocolo', $resposta);
    }

    /** @test */
    public function simula_timeout_ou_erro_de_comunicacao()
    {
        $xmlAssinado = '<?xml version="1.0" encoding="UTF-8"?>
<notaFiscal><numero>TIMEOUT_TEST</numero><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#"></ds:Signature></notaFiscal>';

        $nfeClient = new NFeClient();
        $resposta = $nfeClient->enviarParaSefaz($xmlAssinado);

        // Para números específicos, simula erro de comunicação
        if (strpos($xmlAssinado, 'TIMEOUT_TEST') !== false) {
            $this->assertEquals('erro', $resposta['status']);
            $this->assertStringContainsString('comunicação', $resposta['mensagem']);
        } else {
            $this->assertEquals('sucesso', $resposta['status']);
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}