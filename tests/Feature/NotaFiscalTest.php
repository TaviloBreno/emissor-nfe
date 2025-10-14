<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotaFiscalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function pode_criar_nota_fiscal_no_banco()
    {
        $notaFiscal = NotaFiscal::create([
            'numero' => 'NF001',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 1500.50
        ]);

        $this->assertDatabaseHas('nota_fiscals', [
            'numero' => 'NF001',
            'tipo' => 'saida',
            'valor_total' => '1500.5'
        ]);

        $this->assertEquals('NF001', $notaFiscal->numero);
        $this->assertEquals('saida', $notaFiscal->tipo);
        $this->assertEquals(1500.50, $notaFiscal->valor_total);
    }

    /** @test */
    public function pode_criar_nota_fiscal_via_post()
    {
        $dadosNota = [
            'numero' => 'NF002',
            'data_emissao' => '2025-10-14',
            'tipo' => 'entrada',
            'valor_total' => 750.25
        ];

        $response = $this->postJson('/notas', $dadosNota);

        $response->assertStatus(201);
        $response->assertJson([
            'numero' => 'NF002',
            'tipo' => 'entrada',
            'valor_total' => '750.25'
        ]);

        $this->assertDatabaseHas('nota_fiscals', [
            'numero' => 'NF002',
            'tipo' => 'entrada',
            'valor_total' => '750.25'
        ]);
    }

    /** @test */
    public function valida_campos_obrigatorios_para_criar_nota()
    {
        $response = $this->postJson('/notas', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'numero',
            'data_emissao',
            'tipo',
            'valor_total'
        ]);
    }

    /** @test */
    public function valida_tipo_de_nota_fiscal()
    {
        $dadosNota = [
            'numero' => 'NF003',
            'data_emissao' => '2025-10-14',
            'tipo' => 'invalido',
            'valor_total' => 100.00
        ];

        $response = $this->postJson('/notas', $dadosNota);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tipo']);
    }

    /** @test */
    public function valida_numero_unico_da_nota_fiscal()
    {
        NotaFiscal::create([
            'numero' => 'NF004',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 200.00
        ]);

        $dadosNota = [
            'numero' => 'NF004', // mesmo número
            'data_emissao' => '2025-10-15',
            'tipo' => 'entrada',
            'valor_total' => 300.00
        ];

        $response = $this->postJson('/notas', $dadosNota);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['numero']);
    }
}