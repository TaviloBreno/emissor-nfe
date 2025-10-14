<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Models\EventoNotaFiscal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class CartaCorrecaoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_emitir_carta_correcao_para_nota_autorizada()
    {
        // Cria nota autorizada
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF001',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 1500.50,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e9',
            'data_autorizacao' => Carbon::now()->subHours(2),
            'codigo_verificacao' => '12345678'
        ]);

        $dadosCorrecao = [
            'campo_corrigido' => 'endereco_entrega',
            'valor_anterior' => 'Rua A, 123',
            'valor_novo' => 'Rua B, 456',
            'justificativa' => 'Correção de endereço de entrega conforme solicitação do cliente'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Carta de correção emitida com sucesso'
        ]);

        // Verifica se foi criado registro de correção
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'correcao',
            'justificativa' => 'Correção de endereço de entrega conforme solicitação do cliente'
        ]);

        // Verifica se a resposta contém protocolo e sequência
        $response->assertJsonStructure([
            'success',
            'message',
            'protocolo_evento',
            'sequencia_evento',
            'data_evento'
        ]);
    }

    /** @test */
    public function registra_dados_anteriores_e_novos_na_correcao()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF002',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 750.25,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e8',
            'data_autorizacao' => Carbon::now()->subHours(1),
            'codigo_verificacao' => '87654321'
        ]);

        $dadosCorrecao = [
            'campo_corrigido' => 'dados_adicionais',
            'valor_anterior' => 'Informação incorreta',
            'valor_novo' => 'Informação corrigida',
            'justificativa' => 'Correção de dados adicionais do produto'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao);

        $response->assertStatus(200);

        // Verifica se os dados foram salvos corretamente no evento
        $evento = EventoNotaFiscal::where('nota_fiscal_id', $notaFiscal->id)->first();
        
        $this->assertEquals('correcao', $evento->tipo_evento);
        $this->assertNotNull($evento->dados_anteriores);
        $this->assertNotNull($evento->dados_novos);
        
        $dadosAnteriores = $evento->dados_anteriores;
        $dadosNovos = $evento->dados_novos;
        
        $this->assertEquals('dados_adicionais', $dadosAnteriores['campo_corrigido']);
        $this->assertEquals('Informação incorreta', $dadosAnteriores['valor']);
        $this->assertEquals('Informação corrigida', $dadosNovos['valor']);
    }

    /** @test */
    public function valida_campos_obrigatorios_para_carta_correcao()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF003',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 300.00,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e7',
            'data_autorizacao' => Carbon::now()->subHours(3),
            'codigo_verificacao' => '11111111'
        ]);

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'campo_corrigido',
            'valor_anterior',
            'valor_novo',
            'justificativa'
        ]);
    }

    /** @test */
    public function nao_permite_correcao_nota_nao_autorizada()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF004',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 200.00,
            'status' => 'rascunho'
        ]);

        $dadosCorrecao = [
            'campo_corrigido' => 'endereco_entrega',
            'valor_anterior' => 'Valor A',
            'valor_novo' => 'Valor B',
            'justificativa' => 'Tentativa de correção em nota não autorizada'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Apenas notas fiscais autorizadas podem receber carta de correção'
        ]);
    }

    /** @test */
    public function nao_permite_correcao_nota_cancelada()
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

        $dadosCorrecao = [
            'campo_corrigido' => 'endereco_entrega',
            'valor_anterior' => 'Valor A',
            'valor_novo' => 'Valor B',
            'justificativa' => 'Tentativa de correção em nota cancelada'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Apenas notas fiscais autorizadas podem receber carta de correção'
        ]);
    }

    /** @test */
    public function pode_emitir_multiplas_cartas_correcao_para_mesma_nota()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF006',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 800.00,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e5',
            'data_autorizacao' => Carbon::now()->subHours(4),
            'codigo_verificacao' => '33333333'
        ]);

        // Primeira correção
        $dadosCorrecao1 = [
            'campo_corrigido' => 'endereco_entrega',
            'valor_anterior' => 'Rua A, 123',
            'valor_novo' => 'Rua B, 456',
            'justificativa' => 'Primeira correção - endereço'
        ];

        $response1 = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao1);
        $response1->assertStatus(200);

        // Segunda correção
        $dadosCorrecao2 = [
            'campo_corrigido' => 'dados_adicionais',
            'valor_anterior' => 'Info A',
            'valor_novo' => 'Info B',
            'justificativa' => 'Segunda correção - dados adicionais'
        ];

        $response2 = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao2);
        $response2->assertStatus(200);

        // Verifica se ambas as correções foram registradas
        $eventos = EventoNotaFiscal::where('nota_fiscal_id', $notaFiscal->id)
                                 ->where('tipo_evento', 'correcao')
                                 ->get();

        $this->assertCount(2, $eventos);
        
        // Verifica sequências diferentes
        $sequencias = $eventos->pluck('numero_protocolo_evento')->toArray();
        $this->assertCount(2, array_unique($sequencias));
    }

    /** @test */
    public function valida_campos_permitidos_para_correcao()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF007',
            'data_emissao' => now()->format('Y-m-d'),
            'tipo' => 'saida',
            'valor_total' => 900.00,
            'status' => 'autorizada',
            'numero_protocolo' => '351202510148075a92896e4',
            'data_autorizacao' => Carbon::now()->subHours(5),
            'codigo_verificacao' => '44444444'
        ]);

        // Tenta corrigir campo não permitido (valor total)
        $dadosCorrecao = [
            'campo_corrigido' => 'valor_total',
            'valor_anterior' => '900.00',
            'valor_novo' => '1000.00',
            'justificativa' => 'Tentativa de alteração de valor total (não permitido)'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/correcao", $dadosCorrecao);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'O campo informado não pode ser corrigido via carta de correção'
        ]);
    }
}