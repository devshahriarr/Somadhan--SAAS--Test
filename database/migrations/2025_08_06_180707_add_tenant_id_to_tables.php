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
        $tables = [
            'branches', 'customers', 'banks', 'bank_to_bank_transfers', 'bank_adjustments',
            'employees', 'expenses', 'products', 'purchases', 'promotions', 'actual_payments',
            'transactions', 'pos_settings', 'employee_salaries', 'sms', 'account_transactions',
            'returns', 'company_balances', 'via_sales', 'investors', 'companies', 'user_limits',
            'devices', 'sales', 'stocks', 'stock_transfers', 'warehouses', 'damages',
            'stock_adjustments', 'attributes', 'affiliators', 'affliate_commissions', 'loans',
            'service_sales', 'users'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    if (!Schema::hasColumn($table, 'tenant_id')) {
                        $blueprint->bigInteger('tenant_id')->unsigned()->nullable()->after('id');
                    }
                });
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $foreignKeyName = $table . '_tenant_id_foreign';
                    $blueprint->foreign('tenant_id', $foreignKeyName)->references('id')->on('tenants')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'branches', 'customers', 'banks', 'bank_to_bank_transfers', 'bank_adjustments',
            'employees', 'expenses', 'products', 'purchases', 'promotions', 'actual_payments',
            'transactions', 'pos_settings', 'employee_salaries', 'sms', 'account_transactions',
            'returns', 'company_balances', 'via_sales', 'investors', 'companies', 'user_limits',
            'devices', 'sales', 'stocks', 'stock_transfers', 'warehouses', 'damages',
            'stock_adjustments', 'attributes', 'affiliators', 'affliate_commissions', 'loans',
            'service_sales', 'users'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $foreignKeyName = $table . '_tenant_id_foreign';
                    $blueprint->dropForeign($foreignKeyName);
                    if (Schema::hasColumn($table, 'tenant_id')) {
                        $blueprint->dropColumn('tenant_id');
                    }
                });
            }
        }
    }
};