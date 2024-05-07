<?php

namespace App\Jobs\Calendar;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $user_id, private array $notifications)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Cache::has('calendar_jobs') && Cache::get('calendar_jobs') != 0) {
            Log::info('Waiting for jobs to finish!');
            return;
        }
        if (Cache::has('has_updated')) {
            Log::info('Already sent updates!');
            return;
        }
        Log::info('Sending updates!');
        Cache::set('has_updated', true, now()->addMinutes(15));
    }
}
