<?php

namespace App\Providers;

use App\Models\Code;
use App\Models\CodeTable;
use App\Models\Enquiry;
use App\Models\FormDefinition;
use App\Models\Passport\Client;
use App\Policies\CodePolicy;
use App\Policies\CodeTablePolicy;
use App\Policies\EnquiryPolicy;
use App\Policies\FormDefinitionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CodeTable::class => CodeTablePolicy::class,
        FormDefinition::class => FormDefinitionPolicy::class,
        Enquiry::class => EnquiryPolicy::class,
        Code::class => CodePolicy::class,
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
    }
}
