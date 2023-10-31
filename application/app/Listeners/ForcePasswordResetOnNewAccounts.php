<?php

namespace App\Listeners;

use App\Enums\ForcedPasswordChangeReasons;

class ForcePasswordResetOnNewAccounts
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event->temporaryPassword) {
            $event->user->forcedPasswordChange()->create([
                'reason' => ForcedPasswordChangeReasons::NEW_ACCOUNT->name,
            ]);
        }
    }
}
