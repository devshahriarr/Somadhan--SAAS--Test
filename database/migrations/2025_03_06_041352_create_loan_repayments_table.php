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
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('loan_id')->unsigned();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->date('repayment_date')->nullable();
            $table->decimal('principal_paid', 15, 2)->nullable();
            $table->decimal('interest_paid', 15, 2)->nullable();
            $table->decimal('total_paid', 15, 2)->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();  // For bank-based transactions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
