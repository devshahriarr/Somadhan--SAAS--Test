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
        Schema::create('courier_adds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_id')->unsigned();

            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('api_access_token')->nullable();
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('password')->nullable();
            $table->string('paperfly_key')->nullable();
            $table->foreign('courier_id')->references('id')->on('courier_manages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_adds');
    }
};
