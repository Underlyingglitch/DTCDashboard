<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Niveau;
use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use App\Models\Registration;
use Illuminate\Http\Request;

class WedstrijdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(MatchDay $matchday)
    {
        $this->authorize('create', Wedstrijd::class);

        return view('pages.wedstrijden.create', [
            'matchday' => $matchday,
            'niveaus' => Niveau::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MatchDay $matchday, Request $request)
    {
        $this->authorize('create', Wedstrijd::class);

        $this->validate($request, [
            'index' => 'required|integer',
            'niveaus' => 'required|array|min:1|exists:niveaus,id',
        ]);

        $wedstrijd = $matchday->wedstrijden()->create($request->only('index'));
        $wedstrijd->niveaus()->attach($request->input('niveaus'));

        if (!Setting::getValue('db_write_enabled')) {
            $message = ['warning', 'Wedstrijd toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Wedstrijd succesvol aangemaakt'];
        }

        return redirect()->route('matchdays.show', $matchday)->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wedstrijd $wedstrijd)
    {
        $this->authorize('view', $wedstrijd);

        $registrations = $wedstrijd->registrations()->with('gymnast', 'club', 'niveau', 'team', 'group')->get();

        $groups = $registrations->groupBy('group_id')->sortBy(function ($group, $key) {
            return $group->first()->group->nr;
        })->sortBy(function ($group, $key) {
            return $group->first()->group->baan;
        });

        list($no_team, $registrations) = $registrations->partition(function ($registration) {
            return is_null($registration->team_id);
        });

        $niveaus = $registrations->groupBy('niveau_id')->map(function ($niveau, $key) {
            return $niveau->groupBy('team_id');
        });

        $teams = $wedstrijd->teams()->get();

        return view('pages.wedstrijden.show', [
            'wedstrijd' => $wedstrijd,
            'wedstrijd_baans' => $wedstrijd->baans(),
            'niveaus' => $niveaus,
            'teams' => $teams,
            'groups' => $groups,
            'no_team' => $no_team
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wedstrijd $wedstrijd)
    {
        $this->authorize('update', $wedstrijd);

        return view('pages.wedstrijden.edit', [
            'wedstrijd' => $wedstrijd,
            'niveaus' => Niveau::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wedstrijd $wedstrijd)
    {
        $this->authorize('update', $wedstrijd);

        $this->validate($request, [
            'index' => 'required|integer',
            'niveaus' => 'required|array|min:1|exists:niveaus,id',
        ]);

        $wedstrijd->update($request->only(['index', 'round_settings']));
        $wedstrijd->niveaus()->sync($request->input('niveaus'));

        if (!Setting::getValue('db_write_enabled')) {
            $message = ['warning', 'Wedstrijd wijziging toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Wedstrijd succesvol bijgewerkt'];
        }

        return redirect()->route('wedstrijden.show', $wedstrijd->id)->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wedstrijd $wedstrijd)
    {
        $this->authorize('delete', $wedstrijd);

        $wedstrijd->delete();

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id)->with('success', 'Wedstrijd is verwijderd.');
    }

    public function groupsettings(Request $request, Wedstrijd $wedstrijd)
    {
        $this->authorize('update', $wedstrijd);
        $this->validate($request, [
            'baan1' => 'required|regex:/^[\d*](-[\d*])*$/',
            'baan2' => 'nullable|regex:/^[\d*](-[\d*])*$/|same_length:baan1',
            'baan3' => 'nullable|regex:/^[\d*](-[\d*])*$/|same_length:baan1',
            'baan4' => 'nullable|regex:/^[\d*](-[\d*])*$/|same_length:baan1',
        ]);

        $settings = [[], []];

        $baans = [];
        for ($baan = 1; $baan <= $wedstrijd->baans(); $baan++) {
            $settings[0][] = $request->input('baan' . $baan);
            $baans[] = explode('-', $request->input('baan' . $baan));
        }

        $groups = [];
        // For each round (based on the length of the first baan's array)
        $rounds = count($baans[0]);
        for ($i = 0; $i < $rounds; $i++) {
            // For each baan
            for ($b = 0; $b < $wedstrijd->baans(); $b++) {
                // For each round again, to go over toestellen and rust
                for ($j = 0; $j < $rounds; $j++) {
                    // Assign the respective toestel or rust to the group for each baan
                    $groups[$i][$j][$b] = $baans[$b][$j] == "*" ? 0 : 10 * $b + $baans[$b][$j];
                }
                // Shift the array for the next round
                $last = array_pop($baans[$b]);
                array_unshift($baans[$b], $last);
            }
        }
        $settings[1] = $groups;

        $wedstrijd->update(['group_settings' => $settings]);

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Groepsinstellingen opgeslagen.');
    }

    public function setactive(Wedstrijd $wedstrijd)
    {
        $this->authorize('update', $wedstrijd);

        Setting::setValue('current_wedstrijd', $wedstrijd->id);

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id);
    }
}
