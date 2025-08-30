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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('variation_name', 255)->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->decimal('cost_price', 12, 2);
            $table->decimal('b2b_price', 12, 2)->nullable();
            $table->decimal('b2c_price', 12, 2)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->foreign('size')->references('id')->on('psizes');
            $table->unsignedBigInteger('color')->nullable();
            $table->foreign('color')->references('id')->on('colors');
            $table->string('model_no', 100)->nullable();
            $table->string('quality', 100)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('origin', 100)->nullable();
            $table->integer('low_stock_alert')->nullable()->comment('For a low stock alert in every variation');
            $table->enum('status', ['variant', 'default']);
            $table->enum('productStatus', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variations');
    }
};
