<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\Niveau;
use Livewire\Component;
use App\Models\MatchDay;
use App\Models\TeamScore;
use App\Models\Registration;

class CalculateDoorstroom extends Component
{
    public $competition;
    public $error = null;
    public $match_days_selection;
    public $match_days;
    public $niveaus;
    public $niveau;
    public $amount;

    public $teams;
    public $points_shown = false;
    public $doorstroom;

    private $teampoints = [50, 45, 40, 37, 34, 31, 28, 25, 22, 20, 18, 16, 14, 12, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
    private $points = [50, 45, 40, 37, 34, 31, 28, 26, 24, 22, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1, 1];

    public function mount($competition)
    {
        $this->competition = $competition;
        $this->niveaus = Niveau::all();
        $this->niveau = $this->niveaus->first()->id;
        $this->updateNiveau();
    }

    public function updateNiveau()
    {
        $this->match_days = [];
        $this->match_days_selection = [];
        foreach ($this->competition->match_days as $match_day) {
            if (in_array($this->niveau, $match_day->niveaus->pluck('id')->toArray())) {
                $this->match_days[] = $match_day;
                $this->match_days_selection[$match_day->id] = '0';
            }
        }
    }

    public function process()
    {
        $this->authorize('processDoorstroom', $this->competition);

        if ($this->amount < 1) {
            $this->error = 'Vul een geldig aantal in';
            return;
        }

        // Remove all elements with value 0 from array
        $this->match_days_selection = array_filter($this->match_days_selection);
        if (count($this->match_days_selection) < 1) {
            $this->error = 'Selecteer minimaal 1 wedstrijddag';
            return;
        }

        $teams = Team::where('competition_id', $this->competition->id)->where('niveau_id', $this->niveau)->get();
        $this->teams = $teams->count() > 0;
        $scores = [];
        $ind_scores = [];
        $registration_cache = [];

        foreach ($this->match_days_selection as $match_day_id => $type) {
            $match_day = MatchDay::find($match_day_id);
            if ($this->teams) {
                $teamscores = TeamScore::where('match_day_id', $match_day->id)
                    ->whereIn('team_id', $teams->pluck('id'))
                    ->where('total_score', '>', 0)
                    ->orderByDesc('total_score')
                    ->pluck('team_id')->toArray();
                // dd($teamscores);
                foreach (array_values($teamscores) as $index => $team) {
                    $ind_score = $ind_scores[$team] ?? [];
                    $ind_score[] = $type * $this->teampoints[$index] ?? 0;
                    $score = $scores[$team] ?? 0;
                    $score += $type * $this->teampoints[$index] ?? 0;
                    $scores[$team] = $score;
                    $ind_scores[$team] = $ind_score;
                }
            } else {
                $registrations = Registration::where('match_day_id', $match_day->id)->where('signed_off', 0)->where('niveau_id', $this->niveau)->with(['gymnast', 'club', 'niveau', 'scores' => function ($query) use ($match_day) {
                    $query->where('match_day_id', $match_day->id);
                }])->get()->filter(function ($registration) {
                    return $registration->scores->sum('total') > 0;
                })->sortByDesc(function ($registration) {
                    return $registration->scores->sum('total');
                })->values();
                foreach ($registrations as $index => $registration) {
                    $ind_score = $ind_scores[$registration->startnumber] ?? [];
                    $ind_score[] = $type * $this->teampoints[$index] ?? 0;
                    $score = $scores[$registration->startnumber] ?? 0;
                    $score += $type * $this->points[$index] ?? 0;
                    $scores[$registration->startnumber] = $score;
                    $ind_scores[$registration->startnumber] = $ind_score;
                    $registration_cache[$registration->startnumber] = [
                        'name' => $registration->gymnast->name,
                        'club' => $registration->club->name,
                        'gymnast_id' => $registration->gymnast->id,
                        'club_id' => $registration->club->id,
                    ];
                }
            }
        }
        arsort($scores);

        // dd($scores);

        $doorstroom = [];
        $counter = 0;
        foreach ($scores as $id => $score) {
            $counter++;
            if ($counter > $this->amount) {
                break;
            }
            if ($this->teams) {
                $team = $teams->where('id', $id)->first();
                $registrations = Registration::with(['gymnast', 'club'])->whereIn('id', $team->registrations->pluck('id'))->where('match_day_id', $this->competition->match_days->first()->id)->get()->map(function ($registration) {
                    return [
                        'name' => $registration->gymnast->name,
                        'club' => $registration->club->name,
                        'gymnast_id' => $registration->gymnast->id,
                        'club_id' => $registration->club->id,
                    ];
                });
                $doorstroom[] = ['name' => $team->name, 'registrations' => $registrations, 'scores' => $ind_scores[$team->id], 'total' => $score];
            } else {
                $item = $registration_cache[$id];
                $item['scores'] = $ind_scores[$id];
                $item['total'] = $score;
                $doorstroom[] = $item;
            }
        }
        // dd($doorstroom);
        $this->doorstroom = $doorstroom;
    }

    public function back()
    {
        $this->doorstroom = null;
    }

    public function togglePointsShown()
    {
        $this->points_shown = !$this->points_shown;
    }

    public function render()
    {
        return view('livewire.calculate-doorstroom');
    }
}
