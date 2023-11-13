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
        $adminUserEmail = 'admin@juicy.media';
        if (! User::firstWhere('email', $adminUserEmail)) {
            $user = User::factory()
                ->create([
                    'email' => $adminUserEmail,
                    'name' => 'Juicy Media',
                    'location' => Locations::CARDIFF->name,
                ]);

            $user->syncRoles(['Administrator']);
        }
    }
}
