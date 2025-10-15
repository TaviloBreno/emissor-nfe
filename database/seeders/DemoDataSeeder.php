<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NotaFiscal;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar usuários de demonstração
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@emissor.com',
                'password' => Hash::make('123456'),
                'notas_count' => 25
            ],
            [
                'name' => 'João Silva',
                'email' => 'joao@empresa.com',
                'password' => Hash::make('123456'),
                'notas_count' => 15
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@comercio.com',
                'password' => Hash::make('123456'),
                'notas_count' => 30
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro@loja.com',
                'password' => Hash::make('123456'),
                'notas_count' => 20
            ]
        ];

        foreach ($usuarios as $userData) {
            $notasCount = $userData['notas_count'];
            unset($userData['notas_count']);
            
            // Criar usuário
            $user = User::create($userData);
            
            // Criar notas fiscais para este usuário
            NotaFiscal::factory($notasCount)->create([
                'user_id' => $user->id
            ]);
            
            $this->command->info("Criado usuário: {$user->name} com {$notasCount} notas fiscais");
        }

        // Estatísticas finais
        $totalUsers = User::count();
        $totalNotas = NotaFiscal::count();
        
        $this->command->info("=== DADOS DE DEMONSTRAÇÃO CRIADOS ===");
        $this->command->info("Total de usuários: {$totalUsers}");
        $this->command->info("Total de notas fiscais: {$totalNotas}");
        
        $this->command->info("\n=== CREDENCIAIS DE ACESSO ===");
        foreach ($usuarios as $user) {
            $this->command->info("Email: {$user['email']} | Senha: 123456");
        }
    }
}
