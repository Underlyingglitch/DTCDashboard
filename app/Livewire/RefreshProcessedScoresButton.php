<?php

namespace App\Livewire;

use App\Models\Score;
use Livewire\Component;
use App\Models\ProcessedScore;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

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
        foreach ($groups as $group) {
            for ($toestel = 1; $toestel <= 6; $toestel++) {
                $registrations = $this->wedstrijd->registrations()->where('group_id', $group->id)->where('signed_off', 0)->pluck('startnumber');
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
                    ProcessedScore::destroy([
                        'wedstrijd_id' => $this->wedstrijd->id,
                        'group_id' => $group->id,
                        'toestel' => $toestel,
                    ]);
                }
            }
        }
        Notification::sendNow(Auth::user(), new UserNotification("Status herberekenen", "De scoreverwerking is herladen.", "success"));
    }

    public function render()
    {
        return view('livewire.refresh-processed-scores-button');
    }
}
