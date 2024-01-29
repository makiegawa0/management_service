<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        // 'App\Models\Setting' => 'App\Policies\SettingPolicy',
        // 'App\Models\BankAccount' => 'App\Policies\BankAccountPolicy',
        'App\Models\PaymentRequest' => 'App\Policies\PaymentRequestPolicy',
        'App\Models\AdminUser' => 'App\Policies\AdminUserPolicy',
        'App\Models\Payin' => 'App\Policies\PayInPolicy',
        'App\Models\ApiLog' => 'App\Policies\ApiLogPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
