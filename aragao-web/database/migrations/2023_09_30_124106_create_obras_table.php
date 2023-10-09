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
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('nome');
            $table->date('dt_inicio');
            $table->date('dt_termino')->nullable();
            $table->date('dt_previsao_termino');
            $table->float('valor');
            $table->float('valor_saldo');
            $table->string('endereco_rua');
            $table->string('endereco_bairro');
            $table->string('endereco_numero');
            $table->string('endereco_cidade');
            $table->string('endereco_uf');
            $table->string('endereco_cep');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
