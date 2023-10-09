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
        Schema::create('obras_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_funcionario')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('id_obra')->constrained('obras')->cascadeOnDelete();
            $table->string('funcao');
            $table->string('conselho');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_funcionarios');
    }
};
