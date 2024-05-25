<?php

namespace App\Livewire\Scores;

use App\Models\Score;
use Livewire\Component;
use App\Models\ProcessedScore;

class RefreshProcessedScoresButton extends Component
{
    public $wedstrijd;

    public function mount($wedstrijd)
    {
        $this->wedstrijd = $wedstrijd;
    }

    public function refresh()
    {
        $groups = $this->wedstrijd->groups->get();
        $wedstrijd_registrations = $this->wedstrijd->registrations()->get();
        foreach ($groups as $group) {
            for ($toestel = 1; $toestel <= 6; $toestel++) {
                $registrations = $wedstrijd_registrations->where('group_id', $group->id)->where('signed_off', 0)->pluck('startnumber');
                $score_count = Score::where('match_day_id', $this->wedstrijd->match_day_id)->whereIn('startnumber', $registrations)->where('toestel', $toestel)->count();

                if ($score_count == count($registrations)) {
                    ProcessedScore::updateOrCreate([
                        'wedstrijd_id' => $this->wedstrijd->id,
                        'group_id' => $group->id,
                        'toestel' => $toestel,
                    ], [
                        'completed' => 1,
                    ]);
                } else if ($score_count > 0) {
                    ProcessedScore::updateOrCreate([
                        'wedstrijd_id' => $this->wedstrijd->id,
                        'group_id' => $group->id,
                        'toestel' => $toestel,
                    ], [
                        'completed' => 0,
                    ]);
                } else {
                    $ps = ProcessedScore::where([
                        ['wedstrijd_id', $this->wedstrijd->id],
                        ['group_id', $group->id],
                        ['toestel', $toestel],
                    ])->first();
                    if ($ps) {
                        event(new \App\Events\ProcessedScoreUpdated($ps, true));
                        $ps->delete();
                    }
                }
            }
        }
        $this->dispatch('notification', 'Status herberekenen', 'De scoreverwerking is herladen.', 'success');
    }

    public function render()
    {
        return view('livewire.scores.refresh-processed-scores-button');
    }
}
