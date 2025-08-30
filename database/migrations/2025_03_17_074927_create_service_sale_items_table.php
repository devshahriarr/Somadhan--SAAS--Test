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
        Schema::create('service_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_sale_id')->unsigned();
            $table->foreign('service_sale_id')->references('id')->on('service_sales')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('volume')->nullable();
            $table->decimal('price', 17, 2)->default(0);
            $table->decimal('total', 17, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_sale_items');
    }
};
