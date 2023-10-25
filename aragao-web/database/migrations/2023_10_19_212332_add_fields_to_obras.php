<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->set('tipo_recurso', ['proprio', 'financiamento_caixa']);
            $table->mediumText('descricao_completa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->dropColumn('tipo_recurso');
            $table->dropColumn('descricao_completa');
        });
    }
};
