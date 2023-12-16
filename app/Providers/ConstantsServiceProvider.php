<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ConstantsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::share('export_options', [
            'groups' => 'Groepsindeling',
            'teams' => 'Teamindeling',
            'jury' => 'Jurybriefjes',
            'scores.teams' => 'Team scores',
            'scores.individual' => 'Individuele scores',
        ]);

        View::share('import_options', [
            'registrations' => 'Inschrijvingen',
        ]);

        View::share('toestellen', ['Vloer', 'Voltige', 'Ringen', 'Sprong', 'Brug', 'Rek']);
    }
}
