<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Services\NotaFiscalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExemploCompletoNFeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function exemplo_completo_do_fluxo_de_nota_fiscal()
    {
        // ========================================
        // PASSO 1: CRIAÇÃO DA NOTA FISCAL
        // ========================================
        echo "\n=== CRIANDO NOTA FISCAL ===\n";
        
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF001',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 1500.50,
            'status' => 'rascunho'
        ]);

        echo "✓ Nota Fiscal criada: {$notaFiscal->numero}\n";
        echo "✓ Status inicial: {$notaFiscal->status}\n";
        echo "✓ Valor: R$ {$notaFiscal->valor_total}\n";

        // ========================================
        // PASSO 2: PROCESSAMENTO COMPLETO
        // ========================================
        echo "\n=== PROCESSANDO ENVIO PARA SEFAZ ===\n";
        
        $notaFiscalService = new NotaFiscalService();
        $resultado = $notaFiscalService->processarEnvioCompleto($notaFiscal);

        // Verifica se o processamento foi bem-sucedido
        $this->assertTrue($resultado['sucesso']);
        
        echo "✓ Processamento: {$resultado['mensagem']}\n";
        echo "✓ Protocolo gerado: {$resultado['protocolo']}\n";
        echo "✓ Data autorização: {$resultado['data_autorizacao']}\n";

        // ========================================
        // PASSO 3: VERIFICAÇÃO FINAL
        // ========================================
        echo "\n=== VERIFICAÇÃO FINAL ===\n";
        
        // Recarrega a nota do banco
        $notaFiscal->refresh();
        
        echo "✓ Status final: {$notaFiscal->status}\n";
        echo "✓ Protocolo salvo: {$notaFiscal->numero_protocolo}\n";
        echo "✓ Data autorização: {$notaFiscal->data_autorizacao}\n";
        echo "✓ Código verificação: {$notaFiscal->codigo_verificacao}\n";

        // Assertions para garantir que tudo funcionou
        $this->assertEquals('autorizada', $notaFiscal->status);
        $this->assertNotNull($notaFiscal->numero_protocolo);
        $this->assertNotNull($notaFiscal->data_autorizacao);
        $this->assertNotNull($notaFiscal->codigo_verificacao);
        $this->assertEquals($resultado['protocolo'], $notaFiscal->numero_protocolo);

        // ========================================
        // PASSO 4: CONSULTAS ADICIONAIS
        // ========================================
        echo "\n=== CONSULTAS ADICIONAIS ===\n";
        
        // Consulta notas autorizadas
        $notasAutorizadas = $notaFiscalService->consultarPorStatus('autorizada');
        echo "✓ Total de notas autorizadas: {$notasAutorizadas->count()}\n";
        
        // Consulta status na SEFAZ (simulado)
        $statusSefaz = $notaFiscalService->consultarStatusSefaz($notaFiscal);
        echo "✓ Status na SEFAZ: {$statusSefaz['status']}\n";

        $this->assertCount(1, $notasAutorizadas);
        $this->assertEquals('autorizada', $statusSefaz['status']);

        echo "\n🎉 FLUXO COMPLETO EXECUTADO COM SUCESSO! 🎉\n";
    }

    /** @test */
    public function demonstra_tratamento_de_erro()
    {
        echo "\n=== TESTANDO TRATAMENTO DE ERRO ===\n";
        
        // Cria nota que causará erro (número especial)
        $notaFiscal = NotaFiscal::create([
            'numero' => 'TIMEOUT_TEST',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 100.00,
            'status' => 'rascunho'
        ]);

        echo "✓ Nota criada com número que causará erro: {$notaFiscal->numero}\n";

        $notaFiscalService = new NotaFiscalService();
        $resultado = $notaFiscalService->processarEnvioCompleto($notaFiscal);

        echo "✗ Erro esperado: {$resultado['erro']}\n";
        
        // Verifica se o erro foi tratado corretamente
        $this->assertFalse($resultado['sucesso']);
        
        $notaFiscal->refresh();
        echo "✓ Status mantido como: {$notaFiscal->status}\n";
        
        $this->assertEquals('rascunho', $notaFiscal->status);
        $this->assertNull($notaFiscal->numero_protocolo);

        echo "✓ Tratamento de erro funcionando corretamente!\n";
    }
}