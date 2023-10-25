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
        Schema::table('obras_usuarios', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
        
        Schema::table('obras_usuarios', function (Blueprint $table) {
            $table->set('tipo', ['responsavel', 'responsavel_tecnico', 'arquiteto'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras_usuarios', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
        
        Schema::table('obras_usuarios', function (Blueprint $table) {
            $table->set('tipo', ['responsavel', 'responsavel_tecnico'])->nullable();
        });
    }
};
