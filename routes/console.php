<?php

use App\Models\Team;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('calculate:scores', function () {
    $this->info('Calculating scores');
    $scores = \App\Models\Score::all();
    $bar = $this->output->createProgressBar(count($scores));
    foreach ($scores as $score) {
        $score->touch();
        $bar->advance();
    }
    $bar->finish();
});

Artisan::command('calculate:score-total', function () {
    $this->info('Calculating total score');
    $scores = \App\Models\Score::all();
    $bar = $this->output->createProgressBar(count($scores));
    foreach ($scores as $score) {
        $total = $score->d > 0 ? (($score->d + (10 - $score->e)) - $score->n) : 0;
        if ($total < 0) $total = 0;
        $score->total = $total;
        $score->saveQuietly();
        $bar->advance();
    }
    $bar->finish();
    $this->info("\nDone");
})->purpose('Calculate total score');

Artisan::command('calculate:score-e', function () {
    $this->info('Calculating e scores');
    $scores = \App\Models\Score::all();
    $bar = $this->output->createProgressBar(count($scores));
    foreach ($scores as $score) {
        $score->touch();
        $bar->advance();
    }
    $bar->finish();
    $this->info("\nDone");
})->purpose('Calculate e scores');

Artisan::command('calculate:score-team-place', function () {
    $team_scores = \App\Models\TeamScore::all();
    $bar = $this->output->createProgressBar(count($team_scores));
    foreach ($team_scores as $team_score) {
        $chain = [];
        for ($i = 1; $i <= 6; $i++) {
            $chain[] = new \App\Jobs\Scores\CalculateTeamScore($team_score->team, $i, $team_score->match_day_id);
        }
        \Illuminate\Support\Facades\Bus::chain($chain)->dispatch();
        $bar->advance();
    }
    $this->info("\nScheduled to queue!");
});
