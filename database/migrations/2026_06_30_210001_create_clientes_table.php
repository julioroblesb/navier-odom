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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 200);
            $table->string('ruc', 11)->nullable();
            $table->string('direccion', 300)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->string('contacto_nombre', 150)->nullable();
            $table->string('contacto_telefono', 20)->nullable();
            $table->string('contacto_email', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
