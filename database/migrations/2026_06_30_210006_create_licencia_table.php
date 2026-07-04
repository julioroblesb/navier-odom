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
        Schema::create('licencia', function (Blueprint $table) {
            $table->id();
            $table->text('license_key');
            $table->string('machine_id', 20);
            $table->string('empresa_nombre', 200);
            $table->integer('max_equipos');
            $table->date('fecha_vencimiento');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencia');
    }
};
