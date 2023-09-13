<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ukpidDesktopId = '9a1f65ee-02f1-4119-bff5-d5ef32651f18';
        if (! Client::find($ukpidDesktopId)) {
            Client::factory()
                ->create([
                    'id' => $ukpidDesktopId,
                    'name' => 'UKPID Desktop',
                    'secret' => null,
                    'redirect' => env('DESKTOP_APP_OAUTH_REDIRECT'),
                    'first_party' => true,
                ]);
        }

        $postmanId = '9a1f65ee-00ef-451f-8505-047cda6f087d';
        if (! Client::find($postmanId)) {
            Client::factory()
                ->create([
                    'id' => $postmanId,
                    'name' => 'Postman',
                    'secret' => null,
                    'redirect' => 'https://oauth.pstmn.io/v1/callback',
                    'first_party' => true,
                ]);
        }
    }
}
