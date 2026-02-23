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
            'dscore' => 'D-score formulieren',
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
            'scores' => 'Scores',
        ]);

        View::share('toestellen', ['Vloer', 'Voltige', 'Ringen', 'Sprong', 'Brug', 'Rekstok']);

        View::share('jury_registration_status', [
            'pending' => '<span style="color: orange"><i class="fas fa-clock"></i></span>',
            'scored' => '<span style="color: green"><i class="fas fa-check"></i></span>',
            'signed_off' => '<span style="color: red"><i class="fas fa-times"></i></span>',
            'correction_pending' => '<span style="color: orange"><i class="fas fa-clock-rotate-left"></i></span>',
            'dns' => '<span style="color: red"><i>DNS</i></span>',
        ]);

        View::share('districts', [
            'Landelijk',
            // 'Mid-West',
            // 'Noord',
            // 'Oost',
            'Zuid',
            // 'Zuid-Holland',
        ]);

        View::share('disciplines', [
            'Turnen Heren',
            'Acrobatische Gymnastiek',
            'Trampolinespringen',
            'Turnen Dames',
            'Groepsspringen',
            'Ritmische Gymnastiek',
            'Rhönrad',
            'Dans',
            'Freerunning',
        ]);
    }
}
