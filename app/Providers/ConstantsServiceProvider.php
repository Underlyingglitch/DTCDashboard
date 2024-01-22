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

        View::share('matchday_export_options', [
            'diplomas' => 'Diploma export',
            'trainer_emails' => 'Trainer emails',
        ]);

        View::share('import_options', [
            'registrations' => 'Inschrijvingen',
            'registrations_match' => 'Inschrijvingen (andere wedstrijddag)',
            'trainers' => 'Trainers',
        ]);

        View::share('toestellen', ['Vloer', 'Voltige', 'Ringen', 'Sprong', 'Brug', 'Rek']);
    }
}
