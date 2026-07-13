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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cpf', 14)->unique(); // CPF com máscara ou limpo, garantindo que não duplique
            $table->string('phone', 20)->nullable();
            $table->string('cnh', 20)->unique(); // CNH única para evitar cadastros duplicados
            $table->string('cnh_category', 5);   // Suporta categorias simples ou combinadas (Ex: B, AD, E)
            $table->date('cnh_expiration');      // Data de validade da CNH
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true); // Ativo por padrão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};