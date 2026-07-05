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
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'plan_type')) {
                $table->enum('plan_type', ['Demo', 'Mensual', 'Anual', 'Lifetime'])->default('Demo')->after('estado');
            }
            if (!Schema::hasColumn('tenants', 'demo_expires_at')) {
                $table->date('demo_expires_at')->nullable()->after('plan_type');
            }
            if (!Schema::hasColumn('tenants', 'billing_status')) {
                $table->enum('billing_status', ['Al día', 'Pendiente'])->default('Al día')->after('demo_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'demo_expires_at', 'billing_status']);
        });
    }
};
