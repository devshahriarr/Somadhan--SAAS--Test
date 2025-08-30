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
        Schema::create('courier_manages', function (Blueprint $table) {
            $table->id();
            $table->string('courier_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('base_url')->nullable();
            $table->decimal('current_balance', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_manages');
    }
};
