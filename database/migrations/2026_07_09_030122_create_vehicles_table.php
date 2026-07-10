<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique(); // Placa (única)
            $table->integer('year');           // Ano
            $table->string('brand');           // Marca
            $table->string('model');           // Modelo
            $table->string('color');           // Cor
            $table->string('fuel');            // Combustível
            $table->integer('current_km')->default(0); // KM atual
            $table->string('status')->default('Disponível'); // Status
            $table->text('notes')->nullable(); // Observações (opcional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};