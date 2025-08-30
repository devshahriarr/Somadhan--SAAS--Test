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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('loan_name', 100)->nullable();
            $table->integer('bank_loan_account_id');
            $table->decimal('loan_principal', 15, 2)->nullable();
            $table->decimal('loan_balance', 15, 2)->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->string('repayment_schedule', 20)->nullable();
            $table->integer('loan_duration')->nullable()->check('loan_duration between 0 and 26');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'closed', 'defaulted']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
