<?php

namespace Database\Seeders;

use App\Enums\Locations;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'developer@juicy.media';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Developer',
                    'location' => Locations::CARDIFF->name,
                ]);

            $user->syncRoles(['Developer']);
        }

        $email = 'admin@juicy.media';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Juicy Media',
                    'location' => Locations::CARDIFF->name,
                ]);

            $user->syncRoles(['System Administrator']);
        }

        $email = 'manager@cardiff.com';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Cardiff Manager',
                    'location' => Locations::CARDIFF->name,
                ]);

            $user->syncRoles(['Manager']);
        }

        $email = 'user@cardiff.com';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Cardiff User',
                    'location' => Locations::CARDIFF->name,
                ]);

            $user->syncRoles(['User']);
        }

        $email = 'manager@birmingham.com';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Birmingham Manager',
                    'location' => Locations::BIRMINGHAM->name,
                ]);

            $user->syncRoles(['Manager']);
        }

        $email = 'user@birmingham.com';
        if (! User::firstWhere('email', $email)) {
            $user = User::factory()
                ->create([
                    'email' => $email,
                    'name' => 'Birmingham User',
                    'location' => Locations::BIRMINGHAM->name,
                ]);

            $user->syncRoles(['User']);
        }
    }
}
