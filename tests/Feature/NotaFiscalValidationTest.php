<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotaFiscalValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function valida_campos_obrigatorios_faltando()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'numero',
                'data_emissao',
                'tipo',
                'valor_total'
            ]);
    }

    /** @test */
    public function valida_numero_deve_ser_string()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => 123456, // número em vez de string
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    /** @test */
    public function valida_data_emissao_formato_invalido()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => 'data-invalida', // formato inválido
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data_emissao']);
    }

    /** @test */
    public function valida_data_emissao_futura()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2030-01-01', // data futura
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data_emissao']);
    }

    /** @test */
    public function valida_tipo_deve_ser_enum_valido()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'tipo_invalido', // tipo inválido
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tipo']);
    }

    /** @test */
    public function valida_valor_total_deve_ser_numerico()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => 'valor-invalido' // não numérico
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor_total']);
    }

    /** @test */
    public function valida_valor_total_deve_ser_positivo()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '-100.00' // valor negativo
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor_total']);
    }

    /** @test */
    public function valida_valor_total_zero()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '0.00' // valor zero
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor_total']);
    }

    /** @test */
    public function valida_numero_deve_ter_tamanho_minimo()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '123', // muito curto
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    /** @test */
    public function valida_numero_deve_ter_tamanho_maximo()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '123456789012345678901', // muito longo
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    /** @test */
    public function valida_numero_unico_no_sistema()
    {
        // Cria uma nota existente
        NotaFiscal::factory()->create([
            'numero' => '12345',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345', // número duplicado
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    /** @test */
    public function valida_campos_extras_ignorados()
    {
        $response = $this->actingAs($this->user)
            ->post('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00',
                'campo_extra' => 'valor_extra', // campo não permitido
                'outro_campo' => 'outro_valor'
            ]);

        $response->assertRedirect('/notas')
            ->assertSessionHas('success');

        // Verifica que apenas os campos válidos foram salvos
        $this->assertDatabaseHas('nota_fiscals', [
            'numero' => '12345',
            'user_id' => $this->user->id
        ]);

        // Verifica que campos extras não foram salvos na nota
        $nota = NotaFiscal::where('numero', '12345')->first();
        $this->assertArrayNotHasKey('campo_extra', $nota->toArray());
    }

    /** @test */
    public function valida_tamanho_maximo_protocolo()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00',
                'protocolo_autorizacao' => str_repeat('a', 256) // muito longo
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['protocolo_autorizacao']);
    }

    /** @test */
    public function valida_formato_xml_quando_fornecido()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00',
                'xml_nfe' => 'xml-invalido' // XML inválido
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['xml_nfe']);
    }

    /** @test */
    public function aceita_dados_validos_completos()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/notas', [
                'numero' => '12345',
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertRedirect('/notas')
            ->assertSessionHas('success', 'Nota fiscal criada com sucesso!');

        $this->assertDatabaseHas('nota_fiscals', [
            'numero' => '12345',
            'tipo' => 'saida',
            'valor_total' => '1500.00',
            'user_id' => $this->user->id,
            'status' => 'rascunho'
        ]);
    }
}
