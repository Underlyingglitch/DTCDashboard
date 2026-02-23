<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

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

Artisan::command('app:fetch-calendar {--test}', function () {
    $this->info('Fetching calendar' . ($this->option('test') ? ' (test)' : ''));
    Cache::forget('has_updated');
    \App\Jobs\Calendar\Initialize::dispatch($this->option('test'));
});

Artisan::command('app:fetch-calendar-subscribed', function () {
    $this->info('Fetching calendar for subscribed users');
    \App\Jobs\Calendar\GetDailyUpdates::dispatch();
});

Artisan::command('app:update-resources', function () {
    $this->info('Updating resources');
    \App\Jobs\DGResources\UpdateList::dispatch();
});

Artisan::command('cache:clearsettings', function () {
    $this->info('Clearing settings cache');
    $settings = \App\Models\Setting::$default_types;
    foreach ($settings as $key => $type) {
        \Illuminate\Support\Facades\Cache::forget($key);
    }
});


// Import schedule.php from routes folder
require base_path('routes/schedule.php');
