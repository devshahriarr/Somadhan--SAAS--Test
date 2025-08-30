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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('return_invoice_number');
            $table->integer('sale_id');
            $table->integer('customer_id');
            $table->dateTime('return_date');
            $table->decimal('refund_amount');
            $table->string('return_reason')->nullable();
            $table->decimal('total_return_profit')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('processed_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
