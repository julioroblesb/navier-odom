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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('previous_hash', 64)->nullable()->after('ip_address')->comment('Hash SHA256 of the previous audit log for tamper evidence');
            $table->string('current_hash', 64)->nullable()->after('previous_hash')->comment('Hash SHA256 of this row + previous hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['previous_hash', 'current_hash']);
        });
    }
};
