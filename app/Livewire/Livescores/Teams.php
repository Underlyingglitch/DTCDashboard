<?php

namespace App\Livewire\Livescores;

use Livewire\Component;
use App\Models\MatchDay;
use Illuminate\Support\Facades\Log;

class Teams extends Component
{
    public $teams;
    public $matchday;
    public $niveau;
    public $modalShown;

    public function getListeners()
    {
        return
            ['echo:livescores.' . $this->matchday . ',.TeamScoresUpdated' => 'hydrate'];
    }

    public function mount($matchday, $niveau)
    {
        $this->matchday = $matchday;
        $this->niveau = $niveau;
        $this->modalShown = 0;
        $this->hydrate();
    }

    public function hydrate()
    {
        $matchday = $this->matchday;
        $matchday_teams = MatchDay::find($matchday)->competition->teams()->where('niveau_id', $this->niveau)->with(['registrations' => function ($query) use ($matchday) {
            $query->where('signed_off', 0)->where('match_day_id', $matchday)
                ->with(['gymnast', 'club', 'scores' => function ($query) use ($matchday) {
                    $query->where('match_day_id', $matchday);
                }]);
        }, 'niveau', 'team_scores' => function ($query) use ($matchday) {
            $query->where('match_day_id', $matchday);
        }])->get()->sortByDesc(function ($team) {
            return $team->team_scores->first()->total_score ?? 0;
        });

        $this->teams = [];
        foreach ($matchday_teams as $team) {
            $team_scores = $team->team_scores->first();
            $this->teams[] = [
                'id' => $team->id,
                'name' => $team->name,
                'total' => $team_scores->total_score ?? 0,
                'toestel_scores' => $team_scores->toestel_scores ?? []
            ];
        }
    }

    public function toggleModal($id)
    {
        $this->modalShown = $id;
    }

    public function render()
    {
        return view('livewire.livescores.teams');
    }
}
