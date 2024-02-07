<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Score;
use App\Models\Niveau;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use App\Models\ProcessedScore;
use App\Jobs\CalculateTeamScore;
use App\Jobs\IncrementCounterJob;
use App\Http\Traits\FunctionsTrait;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class ScoreController extends Controller
{
    use FunctionsTrait;

    public function index(Wedstrijd $wedstrijd)
    {
        $this->authorize('process_scores', $wedstrijd);

        $registrations = $wedstrijd->registrations()->with('gymnast', 'club', 'niveau', 'team', 'group')->get();
        $group_count = explode(', ', $wedstrijd->group_amount);
        $groups = [];
        $baans = [];
        $rounds = 0;
        for ($i = 0; $i < $wedstrijd->baans(); $i++) {
            $group_nrs = $registrations
                ->unique('group_id')
                ->pluck('group_id')
                ->toArray();
            $baan_groups = array_filter($group_nrs, function ($group) use ($i) {
                return floor($group / 10) == $i;
            });
            asort($baan_groups);
            $groups[$i] = $baan_groups;
            $baans[$i] = $this->getGroupNrs($group_count[$i], $i);
            $rounds = count($baans[$i]) > $rounds ? count($baans[$i]) : $rounds;
        }

        $pc = ProcessedScore::where('wedstrijd_id', $wedstrijd->id)->get();

        return view('pages.wedstrijden.scores.index', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'baans' => $baans,
            'rounds' => $rounds,
            'pc' => $pc
        ]);
    }

    public function add(Wedstrijd $wedstrijd, $toestel, Group $group)
    {
        $this->authorize('process_scores', $wedstrijd);

        $registrations = $wedstrijd->registrations()->where('group_id', $group->id)->with(['gymnast', 'club', 'niveau', 'scores' => function ($query) use ($wedstrijd) {
            $query->where('match_day_id', $wedstrijd->match_day->id);
        }])->get();

        return view('pages.wedstrijden.scores.add', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'toestel' => $toestel,
            'group' => $group,
        ]);
    }

    public function store(Wedstrijd $wedstrijd, $toestel, Group $group, Request $request)
    {
        $this->authorize('process_scores', $wedstrijd);

        if (!is_null($request->ids)) {
            foreach (explode(',', $request->ids) as $id) if (!in_array($id, $request->s ?? [])) {
                $total = $request['d-' . $id] > 0 ? (($request['d-' . $id] + (10 - $request['e-' . $id])) - $request['n-' . $id]) : 0;
                if ($total < 0) $total = 0;
                Score::firstOrCreate(
                    [

                        'match_day_id' => $wedstrijd->match_day->id,
                        'startnumber' => $id,
                        'toestel' => $toestel,
                    ],
                    [
                        'd' => $request['d-' . $id],
                        'e' => $request['e-' . $id],
                        'n' => $request['n-' . $id],
                        'total' => $total,
                    ]
                );
            }
        }

        ProcessedScore::updateOrCreate([
            'wedstrijd_id' => $wedstrijd->id,
            'group_id' => $group->id,
            'toestel' => $toestel,
        ], [
            'completed' => count($request->s ?? []) > 0 ? 0 : 1,
        ]);

        return redirect()->route('wedstrijden.score.index', $wedstrijd)->with('success', 'Scores zijn toegevoegd.');
    }

    public function correct(Wedstrijd $wedstrijd, Request $request)
    {
        $this->authorize('process_scores', $wedstrijd);

        $this->validate($request, [
            'startnumber' => 'required|numeric',
            'toestel' => 'required|numeric',
        ]);

        $score = Score::where('match_day_id', $wedstrijd->match_day->id)
            ->where('startnumber', $request->startnumber)
            ->where('toestel', $request->toestel)
            ->first();

        // If not found, create new
        $score = $score ?? new Score();
        $d = $request->d ?? $score->d;
        $e = $request->e ?? $score->e;
        $n = $request->n ?? $score->n;
        $total = ($d + (10 - $e)) - $n;
        if ($total < 0) $total = 0;

        $score->update([
            'match_day_id' => $wedstrijd->match_day->id,
            'startnumber' => $request->startnumber,
            'toestel' => $request->toestel,
            'd' => $d,
            'e' => $e,
            'n' => $n,
            'total' => $total,
        ]);

        return redirect()->route('wedstrijden.score.index', $wedstrijd)->with('success', 'Score is bijgewerkt.');
    }

    public function livescores()
    {
        $matchdays = MatchDay::orderBy('date', 'desc')->with(['location', 'competition'])->get();
        return view('pages.livescores.index', [
            'matchdays' => $matchdays,
        ]);
    }

    public function livescores_show(MatchDay $matchday, Niveau $niveau, Request $request)
    {
        if (is_null($niveau->id)) {
            $niveau = $matchday->niveaus->first();
        }
        $teams = $matchday->competition->teams->where('niveau_id', $niveau->id);
        if (count($teams) == 0 && $request->tab == 'teams') {
            return redirect()->route('livescores.show', [$matchday, $niveau]);
        }
        return view('pages.livescores.show', [
            'matchday' => $matchday,
            'niveau' => $niveau,
            'teams' => $teams,
        ]);
    }
}
