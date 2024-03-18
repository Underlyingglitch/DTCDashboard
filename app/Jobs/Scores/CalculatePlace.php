<?php

namespace App\Jobs\Scores;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculatePlace implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Registration $registration)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $registrations = Registration::where('match_day_id', $this->registration->match_day_id)
            ->where('niveau_id', $this->registration->niveau_id)
            ->where('signed_off', false)
            ->with(['scores' => function ($query) {
                $query->where('match_day_id', $this->registration->match_day_id);
            }])
            ->get()
            ->sortByDesc(function ($registration) {
                return $registration->scores->sum('total');
            });
        $previousScore = null;
        $place = 0;
        $same = 1;
        foreach ($registrations as $registration) {
            if ($previousScore !== $registration->scores->sum('total')) {
                $place += $same;
                $same = 1;
            } else {
                $same++;
            }
            $registration->place = $place;
            $registration->saveQuietly();
            $previousScore = $registration->scores->sum('total');
        }
    }
}
