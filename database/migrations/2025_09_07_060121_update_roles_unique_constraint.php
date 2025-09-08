<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'guard_name']);
            $table->unique(['name', 'guard_name', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'guard_name', 'tenant_id']);
            $table->unique(['name', 'guard_name']);
        });
    }
};