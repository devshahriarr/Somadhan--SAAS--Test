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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->decimal('opening_receivable', 12, 2)->nullable();
            $table->decimal('opening_payable', 12, 2)->nullable();
            // // calculated data
            $table->decimal('wallet_balance', 14, 2)->default(0);
            $table->decimal('total_receivable', 20, 2)->default(0);
            $table->decimal('total_payable', 20, 2)->default(0);
            $table->enum('party_type', ['customer', 'supplier', 'both'])->default('both');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
