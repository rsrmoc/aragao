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
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obra')->constrained('obras')->cascadeOnDelete();
            $table->unsignedBigInteger('id_usuario_solicitante');
            $table->unsignedBigInteger('id_usuario_confirmacao')->nullable();
            $table->string('assunto');
            $table->longText('descricao')->nullable();
            $table->dateTime('dt_reuniao');
            $table->date('dt_confirmacao')->nullable();
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
        Schema::dropIfExists('reunioes');
    }
};
