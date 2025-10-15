<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Notificação de aprovação
            DB::table('notifications')->insert([
                'type' => 'App\\Notifications\\NotaFiscalAprovada',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Nota Fiscal Aprovada',
                    'message' => 'A nota fiscal #000000001 foi aprovada pela SEFAZ.',
                    'icon' => 'fas fa-check-circle',
                    'color' => 'green',
                    'url' => '/notas/1',
                    'nota_fiscal_id' => 1,
                    'tipo' => 'aprovacao'
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2)
            ]);

            // Notificação de rejeição
            DB::table('notifications')->insert([
                'type' => 'App\\Notifications\\NotaFiscalRejeitada',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Nota Fiscal Rejeitada',
                    'message' => 'A nota fiscal #000000002 foi rejeitada pela SEFAZ. Motivo: Dados do destinatário inválidos.',
                    'icon' => 'fas fa-times-circle',
                    'color' => 'red',
                    'url' => '/notas/2',
                    'nota_fiscal_id' => 2,
                    'tipo' => 'rejeicao'
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5)
            ]);

            // Notificação de nova nota
            DB::table('notifications')->insert([
                'type' => 'App\\Notifications\\NotaFiscalCriada',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Nova Nota Fiscal',
                    'message' => 'A nota fiscal #000000003 foi criada e está pendente de envio.',
                    'icon' => 'fas fa-file-plus',
                    'color' => 'blue',
                    'url' => '/notas/3',
                    'nota_fiscal_id' => 3,
                    'tipo' => 'criacao'
                ]),
                'read_at' => Carbon::now()->subHour(),
                'created_at' => Carbon::now()->subHours(8),
                'updated_at' => Carbon::now()->subHour()
            ]);

            // Notificação de sistema
            DB::table('notifications')->insert([
                'type' => 'App\\Notifications\\Sistema',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Manutenção Programada',
                    'message' => 'O sistema ficará indisponível das 02:00 às 04:00 para manutenção.',
                    'icon' => 'fas fa-tools',
                    'color' => 'yellow',
                    'url' => null,
                    'tipo' => 'sistema'
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay()
            ]);

            // Notificação de backup
            DB::table('notifications')->insert([
                'type' => 'App\\Notifications\\Sistema',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Backup Concluído',
                    'message' => 'O backup diário dos dados foi realizado com sucesso.',
                    'icon' => 'fas fa-database',
                    'color' => 'green',
                    'url' => '/configuracoes',
                    'tipo' => 'backup'
                ]),
                'read_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subMinutes(30)
            ]);
        }
    }
}
