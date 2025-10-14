<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProtocoloStatusToNotaFiscalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_fiscals', function (Blueprint $table) {
            $table->string('numero_protocolo')->nullable()->after('valor_total');
            $table->enum('status', ['rascunho', 'assinada', 'autorizada', 'cancelada', 'rejeitada'])
                  ->default('rascunho')
                  ->after('numero_protocolo');
            $table->timestamp('data_autorizacao')->nullable()->after('status');
            $table->string('codigo_verificacao')->nullable()->after('data_autorizacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nota_fiscals', function (Blueprint $table) {
            $table->dropColumn(['numero_protocolo', 'status', 'data_autorizacao', 'codigo_verificacao']);
        });
    }
}
