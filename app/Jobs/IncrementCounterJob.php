<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UserNotification;
use App\Events\FinishedScoreCalculation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class IncrementCounterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user, protected $total, protected $wedstrijdid)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $counter = Cache::increment('counter' . $this->wedstrijdid);
        if ($counter >= $this->total) {
            Cache::forget('counter' . $this->wedstrijdid);
            $this->user->notify(new UserNotification("Scoreberekening voltooid", "De scoreberekening is voltooid.", "success"));
        }
    }
}
