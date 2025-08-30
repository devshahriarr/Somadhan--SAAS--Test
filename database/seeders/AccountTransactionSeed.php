<?php

namespace Database\Seeders;

use App\Models\AccountTransaction;
use Illuminate\Database\Seeder;

class AccountTransactionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountTransaction::create([
            'branch_id' => 1,
            'purpose' => 'Bank',
            'account_id' => 1,
            'credit' => 100000000,
            'balance' => 100000000,
        ]);
    }
}
