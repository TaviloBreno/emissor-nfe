<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManifestacaoDestinatarioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_registrar_manifestacao_de_ciencia()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000001'
        ]);

        $dados = [
            'tipo_manifestacao' => 'ciencia',
            'justificativa' => 'Temos ciência da nota fiscal emitida'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Manifestação registrada com sucesso',
                'data' => [
                    'tipo_manifestacao' => 'ciencia',
                    'protocolo' => true // Verifica se tem protocolo
                ]
            ]);

        // Verifica se o evento foi criado
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'manifestacao_ciencia',
            'justificativa' => 'Temos ciência da nota fiscal emitida'
        ]);
    }

    /** @test */
    public function pode_registrar_manifestacao_de_confirmacao()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000002'
        ]);

        $dados = [
            'tipo_manifestacao' => 'confirmacao',
            'justificativa' => 'Confirmamos o recebimento da mercadoria'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Manifestação registrada com sucesso',
                'data' => [
                    'tipo_manifestacao' => 'confirmacao'
                ]
            ]);

        // Verifica se o evento foi criado
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'manifestacao_confirmacao',
            'justificativa' => 'Confirmamos o recebimento da mercadoria'
        ]);
    }

    /** @test */
    public function pode_registrar_manifestacao_de_discordancia()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000003'
        ]);

        $dados = [
            'tipo_manifestacao' => 'discordancia',
            'justificativa' => 'Produto entregue não confere com a nota fiscal'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Manifestação registrada com sucesso',
                'data' => [
                    'tipo_manifestacao' => 'discordancia'
                ]
            ]);

        // Verifica se o evento foi criado
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'manifestacao_discordancia',
            'justificativa' => 'Produto entregue não confere com a nota fiscal'
        ]);
    }

    /** @test */
    public function nao_pode_manifestar_nota_nao_autorizada()
    {
        $notaFiscal = NotaFiscal::factory()->create();

        $dados = [
            'tipo_manifestacao' => 'ciencia',
            'justificativa' => 'Teste de manifestação'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Só é possível manifestar sobre notas fiscais autorizadas'
            ]);
    }

    /** @test */
    public function nao_pode_manifestar_sem_justificativa()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000004'
        ]);

        $dados = [
            'tipo_manifestacao' => 'discordancia'
            // justificativa omitida
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['justificativa']);
    }

    /** @test */
    public function nao_pode_manifestar_com_tipo_invalido()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000005'
        ]);

        $dados = [
            'tipo_manifestacao' => 'tipo_invalido',
            'justificativa' => 'Teste'
        ];

        $response = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tipo_manifestacao']);
    }

    /** @test */
    public function nao_pode_manifestar_nota_inexistente()
    {
        $dados = [
            'tipo_manifestacao' => 'ciencia',
            'justificativa' => 'Teste de manifestação'
        ];

        $response = $this->postJson("/notas/99999/manifestar", $dados);

        $response->assertStatus(404);
    }

    /** @test */
    public function pode_registrar_multiplas_manifestacoes()
    {
        $notaFiscal = NotaFiscal::factory()->autorizada()->create([
            'protocolo_autorizacao' => '135240000000006'
        ]);

        // Primeira manifestação - ciência
        $dados1 = [
            'tipo_manifestacao' => 'ciencia',
            'justificativa' => 'Primeira manifestação - ciência'
        ];

        $response1 = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados1);
        $response1->assertStatus(200);

        // Segunda manifestação - confirmação
        $dados2 = [
            'tipo_manifestacao' => 'confirmacao',
            'justificativa' => 'Segunda manifestação - confirmação'
        ];

        $response2 = $this->postJson("/notas/{$notaFiscal->id}/manifestar", $dados2);
        $response2->assertStatus(200);

        // Verifica se ambos os eventos foram criados
        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'manifestacao_ciencia'
        ]);

        $this->assertDatabaseHas('eventos_nota_fiscal', [
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => 'manifestacao_confirmacao'
        ]);

        // Verifica se existem 2 eventos para esta nota
        $this->assertEquals(2, $notaFiscal->eventos()->count());
    }
}