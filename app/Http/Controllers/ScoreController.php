<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Score;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use App\Models\ProcessedScore;
use App\Jobs\CalculateTeamScore;
use App\Http\Traits\FunctionsTrait;
use Illuminate\Support\Facades\Bus;

class ScoreController extends Controller
{
    use FunctionsTrait;

    public function index(Wedstrijd $wedstrijd)
    {
        $registrations = $wedstrijd->registrations()->with('gymnast', 'club', 'niveau', 'team')->get();
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
        $registrations = $wedstrijd->registrations()->where('group_id', $group->id)->with('gymnast', 'club', 'niveau')->get();

        return view('pages.wedstrijden.scores.add', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'toestel' => $toestel,
            'group' => $group,
        ]);
    }

    public function store(Wedstrijd $wedstrijd, $toestel, Group $group, Request $request)
    {
        if (!is_null($request->ids)) {
            foreach (explode(',', $request->ids) as $id) if (!in_array($id, $request->s ?? [])) Score::firstOrCreate(
                [

                    'match_day_id' => $wedstrijd->match_day->id,
                    'startnumber' => $id,
                    'toestel' => $toestel,
                ],
                [
                    'd' => $request['d-' . $id],
                    'e' => $request['e-' . $id],
                    'n' => $request['n-' . $id],
                    'total' => $request['d-' . $id] > 0 ? (($request['d-' . $id] + (10 - $request['e-' . $id])) - $request['n-' . $id]) : 0,
                ]
            );
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
        $this->validate($request, [
            'startnumber' => 'required|numeric',
            'toestel' => 'required|numeric',
            'd' => 'required|numeric',
            'e' => 'required|numeric',
            'n' => 'required|numeric',
        ]);

        $score = Score::where('match_day_id', $wedstrijd->match_day->id)
            ->where('startnumber', $request->startnumber)
            ->where('toestel', $request->toestel)
            ->first();

        // If not found, create new
        $score = $score ?? new Score();

        $score->update([
            'match_day_id' => $wedstrijd->match_day->id,
            'startnumber' => $request->startnumber,
            'toestel' => $request->toestel,
            'd' => $request->d,
            'e' => $request->e,
            'n' => $request->n,
            'total' => ($request->d + (10 - $request->e)) - $request->n,
        ]);

        return redirect()->route('wedstrijden.score.index', $wedstrijd)->with('success', 'Score is bijgewerkt.');
    }

    public function recalculate(Wedstrijd $wedstrijd)
    {
        // // Get all startnumbers for this wedstrijd
        // $startnumbers = $wedstrijd->registrations()->pluck('startnumber')->toArray();
        // // Get all scores for this wedstrijd
        // $scores = Score::where('match_day_id', $wedstrijd->match_day_id)->whereIn('startnumber', $startnumbers)->get();

        // foreach ($scores as $score) {
        //     if ($score->registration->team) {
        //         // Check which scores count for this team
        //         CheckCountedScores::dispatch($score);
        //     }
        // }
        foreach ($wedstrijd->teams as $team) {
            $jobs = [];
            for ($i = 1; $i <= 6; $i++) {
                $jobs[] = new CalculateTeamScore($team, $i, $wedstrijd->match_day_id);
            }
            Bus::chain($jobs)->dispatch();
        }

        return redirect()->route('wedstrijden.score.index', $wedstrijd)->with('success', 'Scores worden herberekend.');
    }
}
