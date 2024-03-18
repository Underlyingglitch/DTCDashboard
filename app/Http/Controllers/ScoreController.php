<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Score;
use App\Models\Niveau;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use App\Models\ProcessedScore;
use App\Http\Traits\FunctionsTrait;

class ScoreController extends Controller
{
    use FunctionsTrait;

    public function index(Wedstrijd $wedstrijd)
    {
        $this->authorize('process_scores', $wedstrijd);

        $pss = ProcessedScore::where('wedstrijd_id', $wedstrijd->id)->get();

        return view('pages.wedstrijden.scores.index', [
            'wedstrijd' => $wedstrijd,
            'groups' => $wedstrijd->group_settings[1],
            'pss' => $pss,
            'settings' => explode('-', $wedstrijd->round_settings)
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
                Score::firstOrCreate(
                    [

                        'match_day_id' => $wedstrijd->match_day->id,
                        'startnumber' => $id,
                        'toestel' => $toestel,
                    ],
                    [
                        'd' => $request['d-' . $id],
                        'e1' => $request['e-' . $id],
                        'n' => $request['n-' . $id]
                    ]
                );
            }
        }

        return redirect()->route('wedstrijden.score.index', $wedstrijd)->with('success', 'Scores zijn toegevoegd.');
    }

    public function livescores()
    {
        $matchdays = MatchDay::orderBy('date', 'desc')->with(['location', 'competition'])->get();
        return view('pages.livescores.index', [
            'matchdays' => $matchdays,
        ]);
    }
}
