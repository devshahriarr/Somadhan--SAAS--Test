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
        Schema::create('affiliators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('branch_id');

            $table->enum('commission_type', ['fixed', 'percentage'])->default('fixed');
            $table->enum('commission_state', ['against_sale_amount', 'against_profit_amount']);
            $table->decimal('commission_rate', '10', '2')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliators');
    }
};
