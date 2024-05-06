<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Setting;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($ability == 'delete' && !Setting::getValue('db_write_enabled')) {
                return false;
            }
            return $user->hasRole('admin') ? true : null;
        });

        Gate::define('monitor', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('jurytafel', function ($user) {
            return $user->hasRole('jury');
        });
    }
}
