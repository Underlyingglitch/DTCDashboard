<?php

namespace App\Livewire\Livescores;

use Livewire\Component;

class Individual extends Component
{
    public $registrations;
    public $matchday;
    public $niveau;
    public $modalShown;

    public function mount($matchday, $niveau)
    {
        $this->matchday = $matchday;
        $this->niveau = $niveau;
        $this->modalShown = 0;

        $matchday_registrations = $matchday->registrations()->where('signed_off', 0)->where('niveau_id', $this->niveau->id)->with(['gymnast', 'club', 'scores' => function ($query) use ($matchday) {
            $query->where('match_day_id', $matchday->id);
        }])->get()->sortByDesc(function ($registration) {
            return $registration->scores->sum('total');
        });

        foreach ($matchday_registrations as $registration) {
            $this->registrations[] = [
                'id' => $registration->id,
                'name' => $registration->gymnast->name,
                'club' => $registration->club->name,
                'scores' => $registration->scores->sortBy('toestel')->map(function ($score) {
                    return [
                        'id' => $score->id,
                        'toestel' => $score->toestel,
                        'd' => $score->d,
                        'e' => $score->e,
                        'n' => $score->n,
                        'total' => $score->total,
                        'counted' => $score->counted
                    ];
                })->toArray(),
                'total' => $registration->scores->sum('total')
            ];
        }
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
