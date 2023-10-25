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
        Schema::table('obras_etapas', function (Blueprint $table) {
            $table->date('dt_inicio');
            $table->date('dt_previsao');
            $table->date('dt_termino')->nullable();
            $table->date('dt_vencimento');
            $table->float('valor');
            $table->boolean('quitada')->default(false);
            $table->mediumText('descricao_completa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras_etapas', function (Blueprint $table) {
            $table->dropColumn('dt_inicio');
            $table->dropColumn('dt_previsao');
            $table->dropColumn('dt_termino');
            $table->dropColumn('dt_vencimento');
            $table->dropColumn('valor');
            $table->dropColumn('quitada');
            $table->dropColumn('descricao_completa');
        });
    }
};
