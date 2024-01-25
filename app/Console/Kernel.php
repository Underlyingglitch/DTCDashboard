<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->job(new \App\Jobs\SyncDatabase())->everyMinute();
        $schedule->command('queue:monitor database --max=100')->everyMinute();

        if (config('app.env') === 'local') {
            // $schedule->job(new \App\Jobs\SyncDatabase())->everyMinute();
        }

        if (config('app.env') === 'production') {
            $schedule->job(new \App\Jobs\DGResources\UpdateList())->dailyAt('06:00');
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
