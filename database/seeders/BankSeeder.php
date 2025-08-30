<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'id' => 1,
                'branch_id' => 1,
                'name' => 'Cash',
                'branch_name' => 'Dhaka',
                'manager_name' => 'No Name',
                'phone_number' => '0111113333',
                'account' => '343535',
                'email' => 'demo@gmail.com',
                'opening_balance' => '100000000',
                'purpose' => 'Cash',
            ],
            // [
            //     'id' => 2,
            //     'branch_id' => 1,
            //     'name' => 'BKash',
            //     'branch_name' => 'Dhaka',
            //     'manager_name' => 'No Name',
            //     'phone_number' => '0111113333',
            //     'account' => '343535',
            //     'email' => 'demo@gmail.com',
            //     'opening_balance' => '10000',
            //     'purpose' => 'bKash',
            // ],
            // [
            //     'id' => 3,
            //     'branch_id' => 1,
            //     'name' => 'Nagad',
            //     'branch_name' => 'Dhaka',
            //     'manager_name' => 'No Name',
            //     'phone_number' => '0111113333',
            //     'account' => '343535',
            //     'email' => 'demo@gmail.com',
            //     'opening_balance' => '00',
            //     'purpose' => 'Nagad',
            // ],
            // [
            //     'id' => 4,
            //     'branch_id' => 1,
            //     'name' => 'Rocket',
            //     'branch_name' => 'Dhaka',
            //     'manager_name' => 'No Name',
            //     'phone_number' => '0111113333',
            //     'account' => '343535',
            //     'email' => 'demo@gmail.com',
            //     'opening_balance' => '00',
            //     'purpose' => 'rocket',
            // ],
        ];
        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
