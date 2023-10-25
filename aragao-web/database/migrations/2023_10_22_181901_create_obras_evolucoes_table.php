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
        Schema::create('obras_evolucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obra')->constrained('obras')->cascadeOnDelete();
            $table->foreignId('id_etapa')->constrained('obras_etapas')->cascadeOnDelete();
            $table->date('dt_evolucao');
            $table->mediumText('descricao');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_evolucoes');
    }
};
