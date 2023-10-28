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
        Schema::create('reunioes_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obra')->constrained('obras')->cascadeOnDelete();
            $table->foreignId('id_reuniao')->constrained('reunioes')->cascadeOnDelete();
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunioes_usuarios');
    }
};
