<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotaFiscalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;
    protected $user2;
    protected $nota1; // pertence ao user1
    protected $nota2; // pertence ao user2

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user1 = User::factory()->create(['name' => 'Usuário 1']);
        $this->user2 = User::factory()->create(['name' => 'Usuário 2']);
        
        $this->nota1 = NotaFiscal::factory()->create([
            'user_id' => $this->user1->id,
            'numero' => '11111',
            'status' => 'autorizada'
        ]);
        
        $this->nota2 = NotaFiscal::factory()->create([
            'user_id' => $this->user2->id,
            'numero' => '22222',
            'status' => 'autorizada'
        ]);
    }

    /** @test */
    public function usuario_pode_ver_apenas_suas_proprias_notas()
    {
        $response = $this->actingAs($this->user1)
            ->get('/notas');

        $response->assertStatus(200)
            ->assertSee('11111') // vê sua própria nota
            ->assertDontSee('22222'); // não vê a nota do outro usuário
    }

    /** @test */
    public function usuario_nao_pode_acessar_detalhes_de_nota_de_outro_usuario()
    {
        $response = $this->actingAs($this->user1)
            ->get("/notas/{$this->nota2->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function usuario_pode_acessar_detalhes_de_sua_propria_nota()
    {
        // Debug para verificar os dados
        $this->nota1 = $this->nota1->fresh(); // Recarrega do banco
        
        dump('User1 ID: ' . $this->user1->id . ' (tipo: ' . gettype($this->user1->id) . ')');
        dump('Nota1 user_id: ' . $this->nota1->user_id . ' (tipo: ' . gettype($this->nota1->user_id) . ')');
        dump('São iguais com ===: ' . ($this->user1->id === $this->nota1->user_id ? 'true' : 'false'));
        dump('São iguais com ==: ' . ($this->user1->id == $this->nota1->user_id ? 'true' : 'false'));
        
        $response = $this->actingAs($this->user1)
            ->get("/notas/{$this->nota1->id}");

        $response->assertStatus(200)
            ->assertSee('11111');
    }

    /** @test */
    public function usuario_nao_pode_fazer_download_xml_de_nota_de_outro_usuario()
    {
        $response = $this->actingAs($this->user1)
            ->get("/notas/{$this->nota2->id}/download");

        $response->assertStatus(403);
    }

    /** @test */
    public function usuario_pode_fazer_download_xml_de_sua_propria_nota()
    {
        // Adiciona XML para poder fazer download
        $this->nota1->update([
            'xml_nfe' => '<?xml version="1.0" encoding="UTF-8"?><NFe><infNFe>teste</infNFe></NFe>'
        ]);

        $response = $this->actingAs($this->user1)
            ->get("/notas/{$this->nota1->id}/download");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml')
            ->assertHeader('Content-Disposition', 'attachment; filename="NFe_11111.xml"');
    }

    /** @test */
    public function numero_de_nota_deve_ser_unico_apenas_para_o_mesmo_usuario()
    {
        // user1 já tem uma nota com número 11111
        // user2 deve conseguir criar uma nota com o mesmo número
        $response = $this->actingAs($this->user2)
            ->postJson('/notas', [
                'numero' => '11111', // mesmo número da nota do user1
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'numero' => '11111'
            ]);

        // Verifica que existem 2 notas com o mesmo número, mas de usuários diferentes
        $this->assertEquals(2, NotaFiscal::where('numero', '11111')->count());
    }

    /** @test */
    public function usuario_nao_pode_criar_nota_com_numero_duplicado_para_si_mesmo()
    {
        $response = $this->actingAs($this->user1)
            ->postJson('/notas', [
                'numero' => '11111', // mesmo número que já tem
                'data_emissao' => '2025-10-14',
                'tipo' => 'saida',
                'valor_total' => '1500.00'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero']);
    }

    /** @test */
    public function listagem_de_notas_filtra_por_usuario_automaticamente()
    {
        // Cria mais algumas notas para cada usuário
        NotaFiscal::factory()->count(3)->create(['user_id' => $this->user1->id]);
        NotaFiscal::factory()->count(5)->create(['user_id' => $this->user2->id]);

        $response1 = $this->actingAs($this->user1)
            ->getJson('/api/notas'); // usando API para ter JSON response

        $response2 = $this->actingAs($this->user2)
            ->getJson('/api/notas');

        // user1 deve ver apenas suas 4 notas (1 inicial + 3 criadas)
        $this->assertEquals(4, count($response1->json()));
        
        // user2 deve ver apenas suas 6 notas (1 inicial + 5 criadas)  
        $this->assertEquals(6, count($response2->json()));
    }

    /** @test */
    public function guest_nao_pode_acessar_notas()
    {
        $response = $this->get('/notas');
        $response->assertRedirect('/login');

        $response = $this->get("/notas/{$this->nota1->id}");
        $response->assertRedirect('/login');

        $response = $this->get("/notas/{$this->nota1->id}/download");
        $response->assertRedirect('/login');
    }

    /** @test */
    public function nota_retorna_404_quando_nao_existe()
    {
        $response = $this->actingAs($this->user1)
            ->get('/notas/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function apenas_admin_pode_ver_todas_as_notas()
    {
        // Este teste será implementado quando criarmos roles
        $this->markTestSkipped('Roles ainda não implementados');
    }
}