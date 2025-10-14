<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Services\AssinadorService;
use App\Services\GeradorNFService;
use App\Services\NFeClient;
use App\Services\NotaFiscalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessamentoNotaFiscalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_processar_nota_fiscal_completa_ate_autorizacao()
    {
        // Cria uma nota fiscal inicial
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF001',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 1500.50,
            'status' => 'rascunho'
        ]);

        $this->assertEquals('rascunho', $notaFiscal->status);
        $this->assertNull($notaFiscal->numero_protocolo);

        // Processamento completo: gerar XML -> assinar -> enviar -> atualizar status
        $notaFiscalService = new NotaFiscalService();
        $resultado = $notaFiscalService->processarEnvioCompleto($notaFiscal);

        // Verifica o resultado do processamento
        $this->assertTrue($resultado['sucesso']);
        $this->assertArrayHasKey('protocolo', $resultado);
        
        // Recarrega a nota do banco para verificar atualizações
        $notaFiscal->refresh();
        
        // Verifica se o status foi atualizado para autorizada
        $this->assertEquals('autorizada', $notaFiscal->status);
        $this->assertNotNull($notaFiscal->numero_protocolo);
        $this->assertNotNull($notaFiscal->data_autorizacao);
        $this->assertNotNull($notaFiscal->codigo_verificacao);
        
        // Verifica se o protocolo foi salvo corretamente
        $this->assertEquals($resultado['protocolo'], $notaFiscal->numero_protocolo);
    }

    /** @test */
    public function atualiza_status_para_assinada_antes_do_envio()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF002',
            'data_emissao' => '2025-10-14',
            'tipo' => 'entrada',
            'valor_total' => 750.25,
            'status' => 'rascunho'
        ]);

        $notaFiscalService = new NotaFiscalService();
        
        // Apenas assina a nota (sem enviar)
        $xmlAssinado = $notaFiscalService->assinarNota($notaFiscal);
        
        // Verifica se o status mudou para assinada
        $notaFiscal->refresh();
        $this->assertEquals('assinada', $notaFiscal->status);
        
        // Verifica se o XML contém assinatura
        $this->assertStringContainsString('<ds:Signature', $xmlAssinado);
    }

    /** @test */
    public function mantem_status_rascunho_em_caso_de_erro_no_envio()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'TIMEOUT_TEST', // Este número irá causar erro simulado
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 100.00,
            'status' => 'rascunho'
        ]);

        $notaFiscalService = new NotaFiscalService();
        $resultado = $notaFiscalService->processarEnvioCompleto($notaFiscal);

        // Verifica se o processamento falhou
        $this->assertFalse($resultado['sucesso']);
        $this->assertArrayHasKey('erro', $resultado);
        
        // Verifica se o status permaneceu como rascunho
        $notaFiscal->refresh();
        $this->assertEquals('rascunho', $notaFiscal->status);
        $this->assertNull($notaFiscal->numero_protocolo);
        $this->assertNull($notaFiscal->data_autorizacao);
    }

    /** @test */
    public function pode_consultar_notas_por_status()
    {
        // Cria notas com diferentes status
        NotaFiscal::create([
            'numero' => 'NF_RASCUNHO',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 100.00,
            'status' => 'rascunho'
        ]);

        NotaFiscal::create([
            'numero' => 'NF_AUTORIZADA',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 200.00,
            'status' => 'autorizada',
            'numero_protocolo' => '35120250000000123456',
            'data_autorizacao' => now()
        ]);

        $notaFiscalService = new NotaFiscalService();
        
        // Consulta notas por status
        $rascunhos = $notaFiscalService->consultarPorStatus('rascunho');
        $autorizadas = $notaFiscalService->consultarPorStatus('autorizada');

        $this->assertCount(1, $rascunhos);
        $this->assertCount(1, $autorizadas);
        
        $this->assertEquals('NF_RASCUNHO', $rascunhos->first()->numero);
        $this->assertEquals('NF_AUTORIZADA', $autorizadas->first()->numero);
        $this->assertNotNull($autorizadas->first()->numero_protocolo);
    }
}