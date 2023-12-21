<?php

namespace App\Http\Controllers;

use App\Models\Wedstrijd;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Traits\FunctionsTrait;

class WedstrijdExportController extends Controller
{
    use FunctionsTrait;

    public function select(Request $request, Wedstrijd $wedstrijd)
    {
        $this->validate($request, [
            'option' => 'required|not_in:--',
        ]);
        return redirect()->route('wedstrijden.export.' . $request->option, $wedstrijd);
    }

    public function groups(Wedstrijd $wedstrijd)
    {
        $registrations = Registration::where('match_day_id', $wedstrijd->match_day_id)->whereIn('niveau_id', $wedstrijd->niveaus->pluck('id'))->with('gymnast', 'club', 'niveau', 'team')->get();
        $groups = $wedstrijd->groups;
        $teams = [];
        foreach ($groups as $group) {
            foreach ($registrations->where('group_id', $group->id) as $registration) {
                if ($registration->team && !in_array($registration->team->id, array_column($teams, 'id'))) {
                    $teams[] = $registration->team;
                }
            }
        }

        return view('pdf.groups', [
            'wedstrijd' => $wedstrijd,
            'groups' => $groups,
            'registrations' => $registrations,
            'teams' => $teams,
        ]);
    }

    public function teams(Wedstrijd $wedstrijd)
    {
        //dd($wedstrijd->teams()->with('registrations')->get()->flatten()->toArray());
        // dd($wedstrijd->teams()->with(['registrations' => function ($query) use ($wedstrijd) {
        //     $query->where('match_day_id', $wedstrijd->match_day_id)->with('gymnast', 'club', 'niveau');
        // }])->get()->toArray());
        $teams = $wedstrijd->teams()->with(['registrations' => function ($query) use ($wedstrijd) {
            $query->where('match_day_id', $wedstrijd->match_day_id)->with('gymnast', 'club', 'niveau');
        }])->get();
        return view('pdf.teams', [
            'wedstrijd' => $wedstrijd,
            'teams' => $teams
        ]);
    }

    public function jury(Wedstrijd $wedstrijd, $group_nr = null)
    {
        $registrations = $wedstrijd->registrations()->with('gymnast', 'club', 'niveau', 'team')->get();
        $group_count = explode(', ', $wedstrijd->group_amount);
        $groups = [];
        $baans = [];
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
        }

        return view('pdf.jury', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'groups' => $groups,
            'baans' => $baans
        ]);
    }

    public function teamscores(Wedstrijd $wedstrijd)
    {
        $registrations = Registration::where('match_day_id', $wedstrijd->match_day_id)->with('gymnast', 'club', 'niveau', 'team')->get();
        $teams = $wedstrijd->teams()->with('registrations')->get();
        return view('pdf.scores.teams', [
            'wedstrijd' => $wedstrijd,
            'teams' => $teams
        ]);
    }
}
