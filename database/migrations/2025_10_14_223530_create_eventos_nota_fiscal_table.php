<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosNotaFiscalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_nota_fiscal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('nota_fiscals')->onDelete('cascade');
            $table->enum('tipo_evento', ['cancelamento', 'correcao', 'inutilizacao', 'manifestacao_ciencia', 'manifestacao_confirmacao', 'manifestacao_discordancia']);
            $table->text('justificativa');
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->string('numero_protocolo_evento')->nullable();
            $table->string('protocolo')->nullable();
            $table->timestamp('data_evento')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos_nota_fiscal');
    }
}
