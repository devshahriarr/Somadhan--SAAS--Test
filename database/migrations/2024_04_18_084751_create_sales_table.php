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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->date('sale_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->enum('courier_status', ['not_send', 'send'])->default('not_send');
            $table->enum('order_type', ['general', 'online'])->default('general');
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();

            $table->decimal('delivery_charge')->nullable();
            $table->integer('quantity')->default(0); // total product quantity
            $table->decimal('total', 12, 2)->default(0); // total product price
            // $table->string('discount')->nullable(); //user input

            $table->decimal('change_amount', 12, 2)->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('actual_discount', 12, 2)->default(0); // calculated discount
            $table->integer('tax')->nullable(); // calculated tax
            $table->decimal('receivable', 12, 2)->nullable(); // receivable after discount
            $table->decimal('paid', 12, 2)->default(0); // total paid
            $table->decimal('returned', 12, 2)->default(0); // returned amount
            $table->decimal('final_receivable', 12, 2)->default(0); // after return -> receivable
            $table->decimal('due', 12, 2)->default(0); // updated due
            $table->decimal('total_purchase_cost', 12, 2)->nullable(); // updated after return
            $table->decimal('profit', 10, 2)->default(0);
            $table->integer('payment_method')->nullable();

            $table->enum('status', ['paid', 'unpaid', 'partial']);
            $table->enum('order_status', ['draft', 'completed', 'returned', 'updated']);
            $table->unsignedBigInteger('sale_by')->unsigned()->nullable();
            $table->foreign('sale_by')->references('id')->on('users');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
