<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInutilizacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inutilizacoes', function (Blueprint $table) {
            $table->id();
            $table->string('serie')->default('001');
            $table->string('numero_inicial');
            $table->string('numero_final');
            $table->text('justificativa');
            $table->string('numero_protocolo');
            $table->enum('status', ['solicitada', 'autorizada', 'rejeitada'])->default('solicitada');
            $table->timestamp('data_inutilizacao')->useCurrent();
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
        Schema::dropIfExists('inutilizacoes');
    }
}
