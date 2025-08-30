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
        Schema::create('via_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->date('invoice_date')->nullable();
            $table->bigInteger('invoice_number')->nullable();
            $table->string('supplier_name')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('variant_id')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->decimal('paid')->nullable();
            $table->decimal('due')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('via_sales');
    }
};
