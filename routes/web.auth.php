<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\GymnastController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchDaysController;
use App\Http\Controllers\WedstrijdController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WedstrijdExportController;

Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/competitions/{competition}/setactive', [CompetitionController::class, 'setactive'])->name('competitions.setactive');
Route::get('/matchdays/{matchday}/setactive', [MatchDaysController::class, 'setactive'])->name('matchdays.setactive');
Route::get('/wedstrijden/{wedstrijd}/setactive', [WedstrijdController::class, 'setactive'])->name('wedstrijden.setactive');

Route::resource('competitions', CompetitionController::class);
Route::resource('locations', LocationController::class);
Route::resource('trainers', TrainerController::class);
Route::resource('juries', JuryController::class);
Route::resource('gymnasts', GymnastController::class);
Route::resource('users', UserController::class);
Route::resource('clubs', ClubController::class);

Route::get('/livescores', [ScoreController::class, 'livescores'])->name('livescores');

Route::controller(MatchDaysController::class)->name('matchdays.')->group(function () {
    Route::get('competitions/{competition}/matchdays/create', 'create')->name('create');
    Route::post('competitions/{competition}/matchdays', 'store')->name('store');
    Route::get('matchdays/{matchday}', 'show')->name('show');
    Route::get('matchdays/{matchday}/edit', 'edit')->name('edit');
    Route::put('matchdays/{matchday}', 'update')->name('update');
    Route::delete('matchdays/{matchday}', 'destroy')->name('destroy');
});

Route::controller(WedstrijdController::class)->name('wedstrijden.')->prefix('wedstrijden')->group(function () {
    Route::get('/create/{matchday}', 'create')->name('create');
    Route::post('/create/{matchday}', 'store')->name('store');
    Route::prefix('/{wedstrijd}')->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::get('/registration/{registration}/move_group', 'move_group')->name('registration.move_group');
        Route::post('/registration/{registration}/move_group', 'move_group_store')->name('registration.move_group.store');
        Route::get('/registration/{registration}/signoff', 'signoff')->name('registration.signoff');
        Route::controller(WedstrijdExportController::class)->name('export.')->prefix('/export')->group(function () {
            Route::post('/', 'select')->name('select');
            Route::get('/groups', 'groups')->name('groups');
            Route::get('/teams', 'teams')->name('teams');
            Route::get('/jury', 'jury')->name('jury');
            Route::get('/score/teams', 'teamscores')->name('scores.teams');
            Route::get('/score/individual', 'individualscores')->name('scores.individual');
        });
        Route::controller(ScoreController::class)->name('score.')->prefix('/score')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'correct')->name('correct');
            Route::get('/{toestel}/{group}', 'add')->name('add');
            Route::post('/{toestel}/{group}', 'store')->name('store');
            Route::get('/recalculate', 'recalculate')->name('recalculate');
            // Route::get('/{toestel}', 'toestel')->name('toestel');
            // Route::get('/{toestel}/group/{group}', 'group')->name('group');
            // Route::get('/{toestel}/group/{group}/gymnast/{gymnast}', 'gymnast')->name('gymnast');
            // Route::get('/{toestel}/group/{group}/gymnast/{gymnast}/edit', 'edit')->name('edit');
            // Route::put('/{toestel}/group/{group}/gymnast/{gymnast}', 'update')->name('update');
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
