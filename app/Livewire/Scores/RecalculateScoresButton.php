<?php

namespace App\Livewire\Scores;

use Livewire\Component;
use App\Jobs\Scores\CalculateTeamScore;
use App\Jobs\IncrementCounterJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class RecalculateScoresButton extends Component
{
    public $wedstrijd;

    public function mount($wedstrijd)
    {
        $this->wedstrijd = $wedstrijd;
    }

    public function recalculate()
    {
        Notification::sendNow(Auth::user(), new UserNotification("Scoreberekening gestart", "De scoreberekening is gestart."));
        Cache::put('counter' . $this->wedstrijd->id, 0, 600);
        foreach ($this->wedstrijd->teams as $team) {
            $jobs = [];
            for ($i = 1; $i <= 6; $i++) {
                $jobs[] = new CalculateTeamScore($team, $i, $this->wedstrijd->match_day_id);
            }
            $jobs[] = new IncrementCounterJob(Auth::user(), count($this->wedstrijd->teams), $this->wedstrijd->id);
            Bus::chain($jobs)->dispatch();
        }
    }

    public function render()
    {
        return view('livewire.scores.recalculate-scores-button');
    }
}
