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
        Schema::table('equipos', function (Blueprint $table) {
            if (!Schema::hasColumn('equipos', 'sucursal_id')) {
                $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            }
        });

        // Migrate existing data only if they haven't been migrated yet (e.g. equipment with null sucursal_id)
        $clientes = \App\Models\Cliente::all();
        foreach ($clientes as $cliente) {
            // Check if Sede Principal already exists for this client to avoid duplicates
            $sucursal = \App\Models\Sucursal::firstOrCreate(
                ['cliente_id' => $cliente->id, 'nombre' => 'Sede Principal'],
                [
                    'direccion' => $cliente->direccion ?? null,
                    'telefono' => $cliente->telefono ?? null,
                ]
            );

            \App\Models\Equipo::where('cliente_id', $cliente->id)
                ->whereNull('sucursal_id')
                ->update(['sucursal_id' => $sucursal->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
    }
};
