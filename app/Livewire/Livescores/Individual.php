<?php

namespace App\Livewire\Livescores;

use Livewire\Component;
use App\Models\MatchDay;

class Individual extends Component
{
    public $registrations;
    public $matchday;
    public $niveau;
    public $modalShown;

    public $limit = 0;

    public function getListeners()
    {
        return
            ['echo:livescores.' . $this->matchday . ',.ScoreUpdated' => 'hydrate'];
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
        $matchday_registrations = MatchDay::find($matchday)->registrations()->where('signed_off', 0)->where('niveau_id', $this->niveau)->with(['gymnast', 'club', 'scores' => function ($query) use ($matchday) {
            $query->where('match_day_id', $matchday);
        }])->get()->sortByDesc(function ($registration) {
            if ($this->limit) {
                return $registration->scores->take($this->limit)->sortByDesc('total')->sum('total');
            }
            return $registration->scores->sum('total');
        });

        $this->registrations = [];
        foreach ($matchday_registrations as $registration) {
            if ($this->limit) {
                $scores = $registration->scores->take($this->limit);
            } else {
                $scores = $registration->scores;
            }
            $this->registrations[] = [
                'id' => $registration->id,
                'name' => $registration->gymnast->name,
                'club' => $registration->club->name,
                'scores' => $scores->sortBy('toestel')->map(function ($score) {
                    return [
                        'id' => $score->id,
                        'toestel' => $score->toestel,
                        'd' => $score->d,
                        'e' => $score->e_score,
                        'n' => $score->n,
                        'total' => $score->total,
                        'counted' => $score->counted
                    ];
                })->toArray(),
                'total' => $scores->sum('total')
            ];
        }
        // dd($this->registrations);
    }

    public function updateLimit()
    {
        if ($this->limit < 0) {
            $this->limit = 0;
        }
        $this->hydrate();
    }

    public function toggleModal($id)
    {
        $this->modalShown = $id;
    }

    public function render()
    {
        return view('livewire.livescores.individual');
    }
}
