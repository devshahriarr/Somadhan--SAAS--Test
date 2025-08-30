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
        Schema::create('affliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliator_id');
            $table->unsignedBigInteger('sale_id');
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('branch_id');

            $table->enum('status', ['unpaid', 'paid', 'partial paid'])->default('unpaid');
            $table->foreign('affiliator_id')->references('id')->on('affiliators')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affliate_commissions');
    }
};
