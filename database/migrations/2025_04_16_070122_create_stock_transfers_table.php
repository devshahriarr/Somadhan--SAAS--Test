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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('variation_id')->unsigned();
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->date('transfer_date')->nullable();
            $table->integer('from_warehouse_id')->nullable();
            $table->integer('to_warehouse_id')->nullable();
            $table->integer('from_rack_id')->nullable();
            $table->integer('to_rack_id')->nullable();
            $table->integer('from_branch_id')->nullable();
            $table->integer('to_branch_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('completed');
            // $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
