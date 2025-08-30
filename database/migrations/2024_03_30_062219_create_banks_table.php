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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('branch_name', 150)->nullable();
            $table->string('manager_name', 150)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('account')->nullable();
            $table->string('email', 200)->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('update_balance', 12, 2)->default(0);
            $table->string('purpose')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
