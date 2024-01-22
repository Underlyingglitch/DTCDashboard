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

        return redirect()->route('matchdays.show', $matchday)->with('success', 'Wedstrijd is aangemaakt.');
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

        $niveaus = $registrations->groupBy('niveau_id')->map(function ($niveau, $key) {
            return $niveau->groupBy('team_id');
        });

        $teams = $wedstrijd->teams()->get();
        // dd($niveaus);

        // $niveaus = $wedstrijd->teams()->with(['registrations' => function ($query) use ($wedstrijd) {
        //     $query->where('match_day_id', $wedstrijd->match_day_id)->with('gymnast', 'club');
        // }, 'niveau'])->get()->groupBy('niveau_id');

        //$groups = $wedstrijd->registrations()->with('gymnast', 'club', 'niveau', 'group')->get()->groupBy('group_id');

        return view('pages.wedstrijden.show', [
            'wedstrijd' => $wedstrijd,
            'wedstrijd_baans' => $wedstrijd->baans(),
            'niveaus' => $niveaus,
            'teams' => $teams,
            'groups' => $groups
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

        $wedstrijd->update($request->only('index'));
        $wedstrijd->niveaus()->sync($request->input('niveaus'));

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id)->with('success', 'Wedstrijd is aangepast.');
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

    public function move_group(Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('update', $wedstrijd);

        return view('pages.wedstrijden.move_group', [
            'wedstrijd' => $wedstrijd,
            'registration' => $registration
        ]);
    }

    public function move_group_store(Request $request, Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('update', $wedstrijd);

        $this->validate($request, [
            'baan' => 'required|integer|min:0|max:3',
            'group' => 'required|integer|min:0|max:9',
        ]);

        $group = Group::where('nr', $request->input('group') + 1)->where('baan', $request->input('baan') + 1)->first();
        $registration->update(['group_id' => $group->id]);

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Registratie is verplaatst.');
    }

    public function signoff(Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('signoff', $registration);

        $registration->update(['signed_off' => !$registration->signed_off]);

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Registratie is aangepast.');
    }

    public function setactive(Wedstrijd $wedstrijd)
    {
        $this->authorize('update', $wedstrijd);

        Setting::setValue('current_wedstrijd', $wedstrijd->id);

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id);
    }
}
