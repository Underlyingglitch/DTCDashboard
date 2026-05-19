<?php

namespace App\Jobs\Calendar;

use App\Models\CalendarUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Initialize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private bool $test = false)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        CalendarUpdate::truncate();
        if ($this->test) {
            Cache::put('calendar_jobs', 1);
            GetItems::dispatch(5, 2024, 4, 'Zuid');
            return;
        }
        // Initializing
        $current_month = (int)date('m');
        Cache::put('calendar_jobs', 0);
        for ($m = $current_month - 1; $m <= $current_month + 4; $m++) {
            $month = $m;
            $year = (int)date('Y');
            if ($m < 1) {
                $month = 12;
                $year = $year - 1;
            } elseif ($m > 12) {
                $month = $m - 12;
                $year = $year + 1;
            }

            $districts = [
                2 => 'Mid-West',
                5 => 'Noord',
                3 => 'Oost',
                4 => 'Zuid',
                8 => 'Zuid-Holland',
                1 => 'Landelijk'
            ];
            Cache::increment('calendar_jobs', count($districts));
            foreach ($districts as $key => $value) {
                GetItems::dispatch($month, $year, $key, $value);
            }
        }
    }
}
