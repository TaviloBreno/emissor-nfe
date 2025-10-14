<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class CancelamentoNotaFiscalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_cancelar_nota_fiscal_dentro_do_prazo()
    {
        // Cria nota autorizada há 10 horas (dentro do prazo de 24h)
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF001',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 1500.50,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e9',
            'data_autorizacao' => Carbon::now()->subHours(10),
            'codigo_verificacao' => '12345678'
        ]);

        $dadosCancelamento = [
            'justificativa' => 'Cancelamento solicitado pelo cliente devido a erro na emissão'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/cancelar", $dadosCancelamento);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Nota fiscal cancelada com sucesso'
        ]);

        // Verifica se o status foi alterado no banco
        $notaFiscal->refresh();
        $this->assertEquals('cancelada', $notaFiscal->status);
        
        // Verifica se foi criado registro de cancelamento
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'cancelamento',
            'justificativa' => 'Cancelamento solicitado pelo cliente devido a erro na emissão'
        ]);
    }

    /** @test */
    public function nao_pode_cancelar_nota_fora_do_prazo()
    {
        // Cria nota autorizada há 25 horas (fora do prazo de 24h)
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF002',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 750.25,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e8',
            'data_autorizacao' => Carbon::now()->subHours(25),
            'codigo_verificacao' => '87654321'
        ]);

        $dadosCancelamento = [
            'justificativa' => 'Tentativa de cancelamento fora do prazo'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/cancelar", $dadosCancelamento);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Prazo de cancelamento expirado. Notas podem ser canceladas apenas em até 24 horas após a autorização.'
        ]);

        // Verifica se o status permaneceu como autorizada
        $notaFiscal->refresh();
        $this->assertEquals('autorizada', $notaFiscal->status);
    }

    /** @test */
    public function valida_justificativa_obrigatoria_para_cancelamento()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF003',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 300.00,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e7',
            'data_autorizacao' => Carbon::now()->subHours(5),
            'codigo_verificacao' => '11111111'
        ]);

        $response = $this->postJson("/notas/{$notaFiscal->id}/cancelar", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['justificativa']);
    }

    /** @test */
    public function nao_pode_cancelar_nota_nao_autorizada()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF004',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 200.00,
            'status' => 'rascunho'
        ]);

        $dadosCancelamento = [
            'justificativa' => 'Tentativa de cancelar nota não autorizada'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/cancelar", $dadosCancelamento);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Apenas notas fiscais autorizadas podem ser canceladas'
        ]);

        // Status deve permanecer inalterado
        $notaFiscal->refresh();
        $this->assertEquals('rascunho', $notaFiscal->status);
    }

    /** @test */
    public function nao_pode_cancelar_nota_ja_cancelada()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF005',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 500.00,
            'status' => 'cancelada',
            'numero_protocolo' => '351202510148075a92896e6',
            'data_autorizacao' => Carbon::now()->subHours(2),
            'codigo_verificacao' => '22222222'
        ]);

        $dadosCancelamento = [
            'justificativa' => 'Tentativa de cancelar nota já cancelada'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/cancelar", $dadosCancelamento);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Apenas notas fiscais autorizadas podem ser canceladas'
        ]);
    }
}