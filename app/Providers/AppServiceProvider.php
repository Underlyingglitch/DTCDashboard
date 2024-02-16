<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('same_length', function ($attribute, $value, $parameters, $validator) {
            $other = $validator->getData()[$parameters[0]];
            $validator->setCustomMessages([
                'same_length' => 'De lengte van :attribute moet gelijk zijn aan de lengte van ' . $parameters[0] . '.',
            ]);
            return count(explode('-', $value)) == count(explode('-', $other));
        });
    }
}
