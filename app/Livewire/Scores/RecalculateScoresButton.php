<?php

namespace App\Livewire\Scores;

use App\Jobs\IncrementCounterJob;
use App\Jobs\Scores\CalculateTeamScore;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class RecalculateScoresButton extends Component
{
    public $wedstrijd;

    public function mount($wedstrijd)
    {
        $this->wedstrijd = $wedstrijd;
    }

    public function recalculate()
    {
        $this->dispatch('notification', 'Scoreberekening gestart', 'De scoreberekening is gestart.', 'info');
        // Cache::put('counter' . $this->wedstrijd->id, 0, 600);
        // foreach ($this->wedstrijd->teams as $team) {
        //     $jobs = [];
        //     for ($i = 1; $i <= 6; $i++) {
        //         $jobs[] = new CalculateTeamScore($this->wedstrijd->match_day_id, $team->id, $i);
        //     }
        //     $jobs[] = new IncrementCounterJob(Auth::user(), count($this->wedstrijd->teams), $this->wedstrijd->id);
        //     Bus::chain($jobs)->dispatch();
        // }
        Artisan::call('score:recalculate', ['match_day_id' => $this->wedstrijd->match_day_id]);
        $this->dispatch('notification', 'Scoreberekening voltooid', 'De scoreberekening is voltooid.', 'success');
    }

    public function render()
    {
        return view('livewire.scores.recalculate-scores-button');
    }
}
