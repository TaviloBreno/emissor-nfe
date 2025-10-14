<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixNumeroUniqueConstraintForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_fiscals', function (Blueprint $table) {
            // Remove a constraint única do número
            $table->dropUnique(['numero']);
            
            // Adiciona constraint única composta (numero + user_id)
            $table->unique(['numero', 'user_id'], 'nota_fiscals_numero_user_id_unique');
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
            // Remove a constraint composta
            $table->dropUnique('nota_fiscals_numero_user_id_unique');
            
            // Volta a constraint única simples
            $table->unique('numero');
        });
    }
}
