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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->nullable()->unique();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('variation_id')->unsigned()->nullable();
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->integer('warehouse_id')->nullable();
            $table->integer('rack_id')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('stock_age')->nullable();
            $table->boolean('is_Current_stock')->default(false);
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['stock_out', 'available', 'low_stock'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
