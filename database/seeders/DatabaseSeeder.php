<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            'name' => 'Imrul',
            'email' => 'imrul@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 2
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        $user = User::create([
            'name' => 'Afnan',
            'email' => 'afnan@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 2
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        $user = User::create([
            'name' => 'Hosen',
            'email' => 'hosen@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 2
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        $user = User::create([
            'name' => 'Iftekhar',
            'email' => 'iftekhar@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 3
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        $user = User::create([
            'name' => 'Ibrahim',
            'email' => 'ibrahim@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 3
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        $user = User::create([
            'name' => 'Koushik',
            'email' => 'koushik@example.com',
            'password' => Hash::make('123456'),
            'ref_by' => $user->ref_code,
            'type' => 3
        ]);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
    }
}
