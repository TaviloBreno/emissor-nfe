<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotaFiscalWebTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function pode_acessar_pagina_listagem_notas()
    {
        $nota1 = NotaFiscal::factory()->create(['numero' => '001', 'user_id' => $this->user->id]);
        $nota2 = NotaFiscal::factory()->create(['numero' => '002', 'user_id' => $this->user->id]);
        $nota3 = NotaFiscal::factory()->create(['numero' => '003', 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get('/notas');

        $response->assertStatus(200)
            ->assertViewIs('notas.index')
            ->assertSee('Lista de Notas Fiscais')
            ->assertSee('001')
            ->assertSee('002')
            ->assertSee('003')
            ->assertSee('Nova Nota Fiscal');
    }

    /** @test */
    public function guest_nao_pode_acessar_listagem_notas()
    {
        $response = $this->get('/notas');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function pode_acessar_pagina_criar_nota()
    {
        $response = $this->actingAs($this->user)
            ->get('/notas/criar');

        $response->assertStatus(200)
            ->assertViewIs('notas.create')
            ->assertSee('Nova Nota Fiscal')
            ->assertSee('Número da Nota')
            ->assertSee('Data de Emissão')
            ->assertSee('Tipo')
            ->assertSee('Valor Total')
            ->assertSee('Salvar Nota');
    }

    /** @test */
    public function guest_nao_pode_acessar_pagina_criar_nota()
    {
        $response = $this->get('/notas/criar');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function pode_criar_nota_via_formulario()
    {
        $dados = [
            'numero' => '12345',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => '1500.00'
        ];

        $response = $this->actingAs($this->user)
            ->post('/notas', $dados);

        $response->assertRedirect('/notas')
            ->assertSessionHas('success', 'Nota fiscal criada com sucesso!');

        $this->assertDatabaseHas('nota_fiscals', [
            'numero' => '12345',
            'tipo' => 'saida',
            'valor_total' => 1500.00
        ]);
    }

    /** @test */
    public function valida_campos_obrigatorios_ao_criar_nota()
    {
        $response = $this->actingAs($this->user)
            ->post('/notas', []);

        $response->assertSessionHasErrors([
            'numero',
            'data_emissao',
            'tipo',
            'valor_total'
        ]);
    }

    /** @test */
    public function valida_numero_unico_ao_criar_nota()
    {
        NotaFiscal::factory()->create(['numero' => '12345']);

        $dados = [
            'numero' => '12345',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => '1500.00'
        ];

        $response = $this->actingAs($this->user)
            ->post('/notas', $dados);

        $response->assertSessionHasErrors(['numero']);
    }

    /** @test */
    public function pode_visualizar_detalhes_da_nota()
    {
        $nota = NotaFiscal::factory()->create([
            'numero' => '98765',
            'data_emissao' => '2025-10-14',
            'tipo' => 'saida',
            'valor_total' => 2500.00,
            'status' => 'autorizada',
            'numero_protocolo' => '135240000000123'
        ]);

        $response = $this->actingAs($this->user)
            ->get("/notas/{$nota->id}");

        $response->assertStatus(200)
            ->assertViewIs('notas.show')
            ->assertSee('Detalhes da Nota Fiscal')
            ->assertSee('98765')
            ->assertSee('14/10/2025')
            ->assertSee('Saida')
            ->assertSee('R$ 2.500,00')
            ->assertSee('Autorizada')
            ->assertSee('135240000000123')
            ->assertSee('Download XML')
            ->assertSee('Voltar');
    }

    /** @test */
    public function guest_nao_pode_visualizar_detalhes_da_nota()
    {
        $nota = NotaFiscal::factory()->create();

        $response = $this->get("/notas/{$nota->id}");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function retorna_404_para_nota_inexistente()
    {
        $response = $this->actingAs($this->user)
            ->get('/notas/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function pode_fazer_download_do_xml()
    {
        $nota = NotaFiscal::factory()->create([
            'numero' => '11111',
            'status' => 'autorizada'
        ]);

        $response = $this->actingAs($this->user)
            ->get("/notas/{$nota->id}/download");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml')
            ->assertHeader('Content-Disposition', 'attachment; filename="NFe_' . $nota->numero . '.xml"');
    }

    /** @test */
    public function nao_pode_fazer_download_xml_nota_nao_autorizada()
    {
        $nota = NotaFiscal::factory()->create([
            'status' => 'rascunho'
        ]);

        $response = $this->actingAs($this->user)
            ->get("/notas/{$nota->id}/download");

        $response->assertRedirect()
            ->assertSessionHas('error', 'XML disponível apenas para notas autorizadas.');
    }
}