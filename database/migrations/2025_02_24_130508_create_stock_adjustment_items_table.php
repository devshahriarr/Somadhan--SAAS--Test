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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('adjustment_id');
            $table->foreign('adjustment_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
            $table->unsignedBigInteger('variation_id');
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            // Foreign key referencing the Variations table
            $table->integer('previous_quantity'); // The quantity before adjustment
            $table->integer('adjusted_quantity'); // The quantity that was adjusted (increase/decrease)
            $table->integer('final_quantity'); // The final quantity after adjustment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
    }
};
