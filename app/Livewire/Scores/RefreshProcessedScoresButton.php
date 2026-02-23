<?php

namespace App\Livewire\Scores;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class RefreshProcessedScoresButton extends Component
{
    public $wedstrijd;

    public function mount($wedstrijd)
    {
        $this->wedstrijd = $wedstrijd;
    }

    public function refresh()
    {
        Artisan::call('score:refresh-processes-scores', [
            'wedstrijd_id' => $this->wedstrijd->id,
        ]);
        $this->dispatch('notification', 'Status herberekenen', 'De scoreverwerking is herladen.', 'success');
    }

    public function render()
    {
        return view('livewire.scores.refresh-processed-scores-button');
    }
}
