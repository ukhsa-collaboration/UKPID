<?php

namespace App\Models\Passport;

use Database\Factories\ClientFactory;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public static function newFactory()
    {
        return ClientFactory::new();
    }

    /**
     * Determine if the client is a "first party" client.
     *
     * @return bool
     */
    public function firstParty()
    {
        return $this->first_party || parent::firstParty();
    }

    /**
     * Determine if the client should skip the authorization prompt.
     *
     * @return bool
     */
    public function skipsAuthorization()
    {
        return $this->firstParty();
    }
}
