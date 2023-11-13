<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Passport\Client;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Passport::hashClientSecrets();
        Passport::useClientModel(Client::class);
        Passport::tokensExpireIn(now()->addHours(6));
        Passport::refreshTokensExpireIn(now()->addMonths(6));

        // Allow the Administrators to pass all permission checks
        Gate::after(function (User $user, string $ability, ?bool $result, mixed $arguments) {
            if ($user->hasRole('Administrator')) {
                return true;
            }
        });
    }
}
