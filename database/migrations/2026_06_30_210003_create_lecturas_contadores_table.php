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
        Schema::create('lecturas_contadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->dateTime('timestamp');
            $table->integer('copia_bn')->default(0);
            $table->integer('copia_color')->default(0);
            $table->integer('impresion_bn')->default(0);
            $table->integer('impresion_color')->default(0);
            $table->integer('fax_bn')->default(0);
            $table->integer('total_bn')->default(0);
            $table->integer('total_color')->default(0);
            $table->integer('total_global')->default(0);
            $table->integer('duplex')->default(0);
            $table->integer('escaneos')->default(0);
            $table->integer('toner_negro')->nullable();
            $table->integer('toner_cyan')->nullable();
            $table->integer('toner_magenta')->nullable();
            $table->integer('toner_amarillo')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas_contadores');
    }
};
