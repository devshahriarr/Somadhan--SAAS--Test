<?php

namespace Database\Seeders;

use App\Models\PosSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PosSetting::create([
            'id' => 1,
            'company' => 'EIL POS',
            // 'logo' => 'logo.png',
            'address' => 'Banasree,Dhaka,Bangladesh',
            'email' => 'ebbdemo@gmail.com',
            'facebook' => 'https:/www.ebb.com',
            'phone' => '017..',
            'header_text' => 'Demo Header',
            'footer_text' => 'Demo Footer',
            'invoice_type' => 'a4',
            'invoice_logo_type' => 'Logo',
            'barcode_type' => 'single',
            'low_stock' => '5',
            'dark_mode' => '1',
            'created_at' => Carbon::now(),
        ]);
    }
}
