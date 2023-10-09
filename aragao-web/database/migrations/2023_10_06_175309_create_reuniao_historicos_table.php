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
        Schema::create('reuniao_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reuniao')->constrained('reunioes')->cascadeOnDelete();
            $table->unsignedBigInteger('id_usuario');
            $table->enum('situacao', ['agendada', 'confirmada', 'adiada', 'cancelada', 'concluida', 'negada'])->default('agendada');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniao_historicos');
    }
};
