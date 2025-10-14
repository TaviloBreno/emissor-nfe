<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddManifestacaoTypesToEventosNotaFiscalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Para MySQL, alteramos o enum diretamente
        DB::statement("ALTER TABLE eventos_nota_fiscal MODIFY COLUMN tipo_evento ENUM('cancelamento', 'correcao', 'inutilizacao', 'manifestacao_ciencia', 'manifestacao_confirmacao', 'manifestacao_discordancia') NOT NULL");
        
        // Adicionar coluna protocolo se não existir
        Schema::table('eventos_nota_fiscal', function (Blueprint $table) {
            if (!Schema::hasColumn('eventos_nota_fiscal', 'protocolo')) {
                $table->string('protocolo')->nullable()->after('numero_protocolo_evento');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover registros de manifestação primeiro
        DB::statement("DELETE FROM eventos_nota_fiscal WHERE tipo_evento IN ('manifestacao_ciencia', 'manifestacao_confirmacao', 'manifestacao_discordancia')");
        
        // Reverter enum para valores anteriores
        DB::statement("ALTER TABLE eventos_nota_fiscal MODIFY COLUMN tipo_evento ENUM('cancelamento', 'correcao', 'inutilizacao') NOT NULL");
        
        // Remover coluna protocolo se adicionada
        Schema::table('eventos_nota_fiscal', function (Blueprint $table) {
            if (Schema::hasColumn('eventos_nota_fiscal', 'protocolo')) {
                $table->dropColumn('protocolo');
            }
        });
    }
}
