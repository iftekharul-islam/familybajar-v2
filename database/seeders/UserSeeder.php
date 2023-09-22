<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class UserSeeder extends Seeder
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
            'percentage' => [100],
            'manual' => null,
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'type' => 1
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);

        $user = User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('123456'),
            'type' => 2
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);

        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => 'Customer-' . $i + 1,
                'email' => 'customer' . $i + 1 . '@example.com',
                'password' => Hash::make('123456'),
                'ref_by' => User::all()->random()->ref_code,
                'type' => 3
            ]);
            $user->update([
                'ref_code' => $user->id . Random::generate(6)
            ]);
        }
    }
}
