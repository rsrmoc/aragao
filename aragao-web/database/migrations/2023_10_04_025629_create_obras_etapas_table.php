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
        Schema::create('obras_etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obra')->constrained('obras')->cascadeOnDelete();
            $table->unsignedBigInteger('id_usuario');
            $table->string('nome');
            $table->float('porc_etapa');
            $table->float('porc_geral');
            $table->boolean('concluida')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_etapas');
    }
};
