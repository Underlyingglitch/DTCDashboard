<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\GymnastController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchDaysController;
use App\Http\Controllers\OefenstofController;
use App\Http\Controllers\WedstrijdController;
use App\Http\Controllers\DGResourceController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\MatchDaysExportController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\WedstrijdExportController;

Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/wedstrijden/{wedstrijd}/setactive', [WedstrijdController::class, 'setactive'])->name('wedstrijden.setactive');

Route::resource('competitions', CompetitionController::class);
Route::post('/competitions/{competition}/process_doorstroom', [CompetitionController::class, 'process_doorstroom'])->name('competitions.process_doorstroom');
Route::resource('locations', LocationController::class);
Route::resource('trainers', TrainerController::class);
Route::resource('juries', JuryController::class);
Route::resource('gymnasts', GymnastController::class);
Route::resource('users', UserController::class);
Route::resource('feedback', FeedbackController::class);
Route::get('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
Route::resource('clubs', ClubController::class);

Route::controller(ScoreController::class)->name('livescores.')->prefix('livescores')->group(function () {
    Route::get('/', 'livescores')->name('index');
    Route::get('/{matchday}/{niveau?}', \App\Livewire\Livescores\Index::class)->name('show');
});

Route::get('/oefenstof', [OefenstofController::class, 'index'])->name('oefenstof.index');

Route::controller(DGResourceController::class)->group(function () {
    Route::get('/dg_resources', 'index')->name('dg_resources.index');
    Route::get('/dg_resources/{dg_resource}/download', 'download')->name('dg_resources.download');
});

Route::controller(MatchDaysController::class)->name('matchdays.')->group(function () {
    Route::get('competitions/{competition}/matchdays/create', 'create')->name('create');
    Route::post('competitions/{competition}/matchdays', 'store')->name('store');
    Route::prefix('matchdays/{matchday}/')->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::controller(MatchDaysExportController::class)->name('export.')->prefix('export')->group(function () {
            Route::post('/', 'select')->name('select');
            Route::get('/diplomas', 'diplomas')->name('diplomas');
            Route::get('/trainer_emails', 'trainer_emails')->name('trainer_emails');
        });
    });
});

Route::controller(WedstrijdController::class)->name('wedstrijden.')->prefix('wedstrijden')->group(function () {
    Route::get('/create/{matchday}', 'create')->name('create');
    Route::post('/create/{matchday}', 'store')->name('store');
    Route::prefix('/{wedstrijd}')->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::post('/groupsettings', 'groupsettings')->name('groupsettings');
        Route::controller(WedstrijdExportController::class)->name('export.')->prefix('/export')->group(function () {
            Route::post('/', 'select')->name('select');
            Route::get('/groups', 'groups')->name('groups');
            Route::get('/teams', 'teams')->name('teams');
            Route::get('/jury', 'jury')->name('jury');
            Route::get('/dscore', 'dscore')->name('dscore');
            Route::get('/score/teams', 'teamscores')->name('scores.teams');
            Route::get('/score/individual', 'individualscores')->name('scores.individual');
        });
        Route::controller(ScoreController::class)->name('score.')->prefix('/score')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{toestel}/{group}', 'add')->name('add');
            Route::post('/{toestel}/{group}', 'store')->name('store');
            Route::get('/recalculate', 'recalculate')->name('recalculate');
        });
    });
});

Route::controller(TeamController::class)->name('teams.')->prefix('wedstrijden/{wedstrijd}/teams')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{team}/edit', 'edit')->name('edit');
    Route::put('/{team}', 'update')->name('update');
    Route::delete('/{team}', 'destroy')->name('destroy');
    Route::name('registration.')->prefix('/registration/{registration}')->group(function () {
        Route::get('/remove', 'registration_remove')->name('remove');
        Route::get('/add', 'registration_add')->name('add');
        Route::post('/add', 'registration_add_store')->name('add.store');
    });
});

Route::controller(ImportController::class)->name('import.')->prefix('import')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
});

Route::controller(SettingsController::class)->name('settings.')->prefix('settings')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/set/{setting}/{value}', 'set')->name('set');
    Route::get('/database', 'database')->name('database');
    Route::get('/database/process', 'database_process')->name('database.process');
    Route::get('/database/compare', 'compare_databases')->name('database.compare');
});

Route::controller(MonitorController::class)->name('monitor.')->prefix('monitor')->group(function () {
    Route::get('/', 'index')->name('index');
});
