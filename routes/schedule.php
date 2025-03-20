<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

$email_on_failure = 'rickokkersen@gmail.com';

Schedule::job(new \App\Jobs\SyncDatabase(), 'sync')
    ->everyFifteenSeconds()
    ->environments(['local'])
    ->emailOutputOnFailure($email_on_failure);

Schedule::command('app:update-resources')
    ->dailyAt('06:00')
    ->environments(['production'])
    ->emailOutputOnFailure($email_on_failure);

Schedule::command('app:fetch-calendar')
    ->dailyAt('03:00')
    ->environments(['production'])
    ->emailOutputOnFailure($email_on_failure);

Schedule::command('app:fetch-calendar-subscribed')
    ->everyTenMinutes()
    ->skip(function () {
        return Cache::get('no_daily_updates', false);
    })
    ->between('8:00', '22:00')
    ->environments(['production'])
    ->emailOutputOnFailure($email_on_failure);
