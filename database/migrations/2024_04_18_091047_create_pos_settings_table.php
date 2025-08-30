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
        Schema::create('pos_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('phone')->nullable();
            // $table->string('page_link')->nullable();
            // $table->string('website')->nullable();
            $table->string('header_text')->nullable();
            $table->string('footer_text')->nullable();
            // $table->boolean('sale_over_sotck')->default(0);
            $table->enum('invoice_type', ['a4', 'a5', 'pos']);
            $table->enum('invoice_logo_type', ['Logo', 'Name', 'Both'])->default('Logo');
            $table->enum('barcode_type', ['single', 'a4']);
            $table->integer('low_stock')->default(10);
            $table->boolean('dark_mode')->default(0);
            $table->boolean('discount')->default(0);
            $table->boolean('tax')->default(0);
            $table->boolean('barcode')->default(0);
            $table->boolean('via_sale')->default(0);
            $table->boolean('selling_price_edit')->default(0);
            $table->boolean('purchase_price_edit')->default(0);
            $table->boolean('warranty')->default(0);
            $table->enum('sale_price_type', ['b2c_price', 'b2b_price'])->default('b2c_price');
            $table->boolean('invoice_payment')->default(0);
            $table->boolean('affliate_program')->default(0);
            $table->boolean('sell_commission')->default(0);
            $table->boolean('link_invoice_payment')->default(0);
            $table->boolean('sale_sms')->default(0);
            $table->boolean('transaction_sms')->default(0);
            $table->boolean('profile_payment_sms')->default(0);
            $table->boolean('link_invoice_payment_sms')->default(0);
            $table->boolean('manufacture_date')->default(0);
            $table->boolean('expiry_date')->default(0);
            $table->boolean('selling_price_update')->default(0);
            $table->boolean('bulk_update')->default(0);
            $table->boolean('auto_genarate_invoice')->default(0);
            $table->boolean('product_set_low_stock')->default(0);
            $table->boolean('low_stock_alert')->default(0);
            $table->boolean('color_view')->default(0);
            $table->boolean('size_view')->default(0);
            $table->boolean('purchase_hands_on_discount')->default(0);
            $table->boolean('purchase_individual_product_discount')->default(0);
            $table->boolean('sale_with_low_price')->default(0);
            $table->boolean('sale_commission')->default(0);
            $table->boolean('purchase_price_update')->default(0);
            $table->boolean('sms_manage')->default(0);
            $table->boolean('due_reminder')->default(0);
            $table->boolean('courier_management')->default(0);
            $table->boolean('party_ways_rate_kit')->default(0);
            $table->boolean('drag_and_drop')->default(0);
            $table->boolean('sale_without_stock')->default(0);
            $table->boolean('sale_hands_on_discount')->default(0);
            $table->boolean('sale_page')->default(1);
            $table->boolean('purchase_page')->default(1);
            $table->boolean('make_invoice_print')->default(1);
            $table->boolean('multiple_category')->default(0);
            $table->boolean('elastic_search')->default(0);
            // $table->boolean('purchase_sms')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_settings');
    }
};
