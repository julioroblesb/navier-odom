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
        $tables = ['users', 'clientes', 'equipos', 'contratos', 'alertas', 'lecturas_contadores'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'clientes', 'equipos', 'contratos', 'alertas', 'lecturas_contadores'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropForeign(['tenant_id']);
                $t->dropColumn('tenant_id');
            });
        }
    }
};
