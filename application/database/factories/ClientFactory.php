<?php

namespace Database\Factories;

use App\Models\Passport\Client;
use Laravel\Passport\Database\Factories\ClientFactory as PassportClientFactory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ClientFactory extends PassportClientFactory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return $this->ensurePrimaryKeyIsSet([
            'user_id' => null,
            'name' => $this->faker->company(),
            'secret' => Str::random(40),
            'redirect' => $this->faker->url(),
            'first_party_client' => false,
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);
    }
}

