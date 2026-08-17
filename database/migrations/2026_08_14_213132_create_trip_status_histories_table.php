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
        Schema::create('trip_status_histories', function (Blueprint $table) {
            $table->id();

            // Viagem relacionada
            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            // Novo status da viagem
            $table->string('status');

            // Observação/motivo da alteração
            $table->text('observations')->nullable();

            // Usuário que realizou a alteração
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_status_histories');
    }
};