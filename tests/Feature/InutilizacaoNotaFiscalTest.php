<?php

namespace Tests\Feature;

use App\Models\Inutilizacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InutilizacaoNotaFiscalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_inutilizar_numero_unico()
    {
        $dadosInutilizacao = [
            'serie' => '001',
            'numero_inicial' => '100',
            'numero_final' => '100',
            'justificativa' => 'Erro na sequência numérica, necessário inutilizar o número 100'
        ];

        $response = $this->postJson('/notas/inutilizar', $dadosInutilizacao);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Numeração inutilizada com sucesso'
        ]);

        // Verifica se foi gravado no banco
        $this->assertDatabaseHas('inutilizacoes', [
            'serie' => '001',
            'numero_inicial' => '100',
            'numero_final' => '100',
            'justificativa' => 'Erro na sequência numérica, necessário inutilizar o número 100',
            'status' => 'autorizada'
        ]);

        // Verifica se a resposta contém protocolo
        $response->assertJsonStructure([
            'success',
            'message',
            'protocolo',
            'data_inutilizacao'
        ]);
    }

    /** @test */
    public function pode_inutilizar_faixa_de_numeros()
    {
        $dadosInutilizacao = [
            'serie' => '001',
            'numero_inicial' => '200',
            'numero_final' => '250',
            'justificativa' => 'Problemas técnicos na emissão, inutilizando faixa de 200 a 250'
        ];

        $response = $this->postJson('/notas/inutilizar', $dadosInutilizacao);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Numeração inutilizada com sucesso'
        ]);

        // Verifica se foi gravado no banco
        $this->assertDatabaseHas('inutilizacoes', [
            'serie' => '001',
            'numero_inicial' => '200',
            'numero_final' => '250',
            'status' => 'autorizada'
        ]);
    }

    /** @test */
    public function valida_campos_obrigatorios_para_inutilizacao()
    {
        $response = $this->postJson('/notas/inutilizar', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'numero_inicial',
            'numero_final',
            'justificativa'
        ]);
    }

    /** @test */
    public function valida_que_numero_final_maior_ou_igual_inicial()
    {
        $dadosInutilizacao = [
            'serie' => '001',
            'numero_inicial' => '100',
            'numero_final' => '50', // Número final menor que inicial
            'justificativa' => 'Teste de validação'
        ];

        $response = $this->postJson('/notas/inutilizar', $dadosInutilizacao);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['numero_final']);
    }

    /** @test */
    public function valida_tamanho_minimo_justificativa()
    {
        $dadosInutilizacao = [
            'serie' => '001',
            'numero_inicial' => '300',
            'numero_final' => '300',
            'justificativa' => 'Curta' // Muito curta
        ];

        $response = $this->postJson('/notas/inutilizar', $dadosInutilizacao);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['justificativa']);
    }

    /** @test */
    public function nao_permite_inutilizar_faixa_ja_inutilizada()
    {
        // Cria inutilização existente
        Inutilizacao::create([
            'serie' => '001',
            'numero_inicial' => '400',
            'numero_final' => '450',
            'justificativa' => 'Primeira inutilização',
            'numero_protocolo' => 'INUT123456789',
            'status' => 'autorizada'
        ]);

        // Tenta inutilizar número dentro da faixa já inutilizada
        $dadosInutilizacao = [
            'serie' => '001',
            'numero_inicial' => '420',
            'numero_final' => '430',
            'justificativa' => 'Tentativa de inutilização duplicada'
        ];

        $response = $this->postJson('/notas/inutilizar', $dadosInutilizacao);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error' => 'Numeração já foi inutilizada anteriormente'
        ]);
    }

    /** @test */
    public function pode_consultar_inutilizacoes_por_serie()
    {
        // Cria algumas inutilizações
        Inutilizacao::create([
            'serie' => '001',
            'numero_inicial' => '100',
            'numero_final' => '150',
            'justificativa' => 'Teste 1',
            'numero_protocolo' => 'INUT111',
            'status' => 'autorizada'
        ]);

        Inutilizacao::create([
            'serie' => '002',
            'numero_inicial' => '200',
            'numero_final' => '250',
            'justificativa' => 'Teste 2',
            'numero_protocolo' => 'INUT222',
            'status' => 'autorizada'
        ]);

        $response = $this->getJson('/notas/inutilizacoes?serie=001');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.serie', '001');
        $response->assertJsonPath('data.0.numero_inicial', '100');
    }
}