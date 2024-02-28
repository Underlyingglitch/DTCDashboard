<?php

namespace App\Http\Controllers;

use App\Models\Wedstrijd;
use App\Models\Registration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $groups = $wedstrijd->groups->orderBy('id')->get();
        $teams = [];
        foreach ($groups as $group) {
            foreach ($registrations->where('group_id', $group->id) as $registration) {
                if ($registration->team && !in_array($registration->team->id, array_column($teams, 'id'))) {
                    $teams[] = $registration->team;
                }
            }
        }
        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.groups', [
                'wedstrijd' => $wedstrijd,
                'groups' => $groups,
                'registrations' => $registrations,
                'teams' => $teams,
            ]);
        }
        $pdf = Pdf::loadView('pdf.groups', [
            'wedstrijd' => $wedstrijd,
            'groups' => $groups,
            'registrations' => $registrations,
            'teams' => $teams,
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' . $wedstrijd->index . '.pdf"',
        ]);
    }

    public function teams(Wedstrijd $wedstrijd)
    {
        $niveaus = $wedstrijd->teams()->with(['registrations' => function ($query) use ($wedstrijd) {
            $query->where('match_day_id', $wedstrijd->match_day_id)->with('gymnast', 'club');
        }, 'niveau'])->get()->groupBy('niveau_id');

        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.teams', [
                'wedstrijd' => $wedstrijd,
                'niveaus' => $niveaus
            ]);
        }
        $pdf = Pdf::loadView('pdf.teams', [
            'wedstrijd' => $wedstrijd,
            'niveaus' => $niveaus
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' .
                $wedstrijd->index . ' teams.pdf"',
        ]);
    }

    public function jury(Wedstrijd $wedstrijd)
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

        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.jury', [
                'wedstrijd' => $wedstrijd,
                'registrations' => $registrations,
                'groups' => $groups,
                'baans' => $baans
            ]);
        }
        $pdf = Pdf::loadView('pdf.jury', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'groups' => $groups,
            'baans' => $baans
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Jurybriefjes W' . $wedstrijd->index . '.pdf"',
        ]);
    }

    public function dscore(Wedstrijd $wedstrijd)
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

        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.d-score', [
                'wedstrijd' => $wedstrijd,
                'registrations' => $registrations,
                'groups' => $groups,
                'baans' => $baans
            ]);
        }
        $pdf = Pdf::loadView('pdf.d-score', [
            'wedstrijd' => $wedstrijd,
            'registrations' => $registrations,
            'groups' => $groups,
            'baans' => $baans
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="D-score formulieren W' . $wedstrijd->index . '.pdf"',
        ]);
    }

    public function teamscores(Wedstrijd $wedstrijd)
    {
        $niveaus = $wedstrijd->teams()->with(['registrations' => function ($query) use ($wedstrijd) {
            $query->where('signed_off', 0)->where('match_day_id', $wedstrijd->match_day_id)
                ->with(['gymnast', 'club', 'scores' => function ($query) use ($wedstrijd) {
                    $query->where('match_day_id', $wedstrijd->match_day_id);
                }]);
        }, 'niveau', 'team_scores' => function ($query) use ($wedstrijd) {
            $query->where('match_day_id', $wedstrijd->match_day_id);
        }])->get()->groupBy('niveau_id')->map(function ($group) {
            return $group->sortByDesc(function ($team) {
                return $team->team_scores->first()->total_score ?? 0;
            });
        });

        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.scores.teams', [
                'wedstrijd' => $wedstrijd,
                'niveaus' => $niveaus
            ]);
        }
        $pdf = Pdf::loadView('pdf.scores.teams', [
            'wedstrijd' => $wedstrijd,
            'niveaus' => $niveaus
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Uitslag ' . $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' .
                $wedstrijd->index . ' teams.pdf"',
        ]);
    }

    public function individualscores(Wedstrijd $wedstrijd)
    {
        $niveaus = $wedstrijd->registrations()->where('signed_off', 0)->with(['gymnast', 'club', 'niveau', 'scores' => function ($query) use ($wedstrijd) {
            $query->where('match_day_id', $wedstrijd->match_day_id);
        }])->get()->groupBy('niveau_id')->map(function ($group) {
            return $group->sortByDesc(function ($registration) {
                return $registration->scores->sum('total');
            });
        });

        // If debug is enabled, return the view instead of downloading the pdf
        if (config('app.debug')) {
            return view('pdf.scores.individual', [
                'wedstrijd' => $wedstrijd,
                'niveaus' => $niveaus
            ]);
        }
        $pdf = Pdf::loadView('pdf.scores.individual', [
            'wedstrijd' => $wedstrijd,
            'niveaus' => $niveaus
        ]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Uitslag ' . $wedstrijd->match_day->location->name . ' ' . $wedstrijd->match_day->date->format('d-m-Y') . ' W' .
                $wedstrijd->index . '.pdf"',
        ]);
    }
}
