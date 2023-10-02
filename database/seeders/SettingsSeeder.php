<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use App\Models\GlobalWithdrawSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GlobalSetting::create([
            'hierarchy' => 1,
            'percentage' => [80],
            'manual' => null,
            'dealer' => 10,
            'buyer' => 10,
        ]);
        GlobalWithdrawSetting::create([
            'minimum_withdraw_amount' => 100,
            'company_charge' => 10,
        ]);
    }
}
