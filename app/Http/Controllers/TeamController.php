<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Wedstrijd;
use App\Models\Registration;
use Illuminate\Http\Request;

class TeamController extends Controller
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
    public function create(Wedstrijd $wedstrijd)
    {
        $this->authorize('create', \App\Models\Team::class);

        return view('pages.teams.create', [
            'wedstrijd' => $wedstrijd,
            'niveaus' => $wedstrijd->niveaus->pluck('full_name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Wedstrijd $wedstrijd)
    {
        $this->authorize('create', \App\Models\Team::class);

        $this->validate($request, [
            'name' => 'required|string',
            'niveau_id' => 'required|exists:niveaus,id',
        ]);

        $wedstrijd->competition->teams()->create([
            'name' => $request->input('name'),
            'competition_id' => $wedstrijd->competition->id,
            'niveau_id' => $request->input('niveau_id'),
        ]);

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Team is aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wedstrijd $wedstrijd, Team $team)
    {
        $this->authorize('update', $team);

        return view('pages.teams.edit', [
            'team' => $team,
            'niveaus' => $wedstrijd->niveaus->pluck('full_name', 'id'),
            'wedstrijd' => $wedstrijd,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wedstrijd $wedstrijd, Team $team)
    {
        $this->authorize('update', $team);

        $this->validate($request, [
            'name' => 'required|string',
            'niveau_id' => 'required|exists:niveaus,id'
        ]);

        $team->update([
            'name' => $request->input('name'),
            'niveau_id' => $request->input('niveau_id'),
        ]);

        foreach ($team->registrations as $registration) {
            if ($registration->niveau_id != $team->niveau_id) {
                $registration->team_id = null;
                $registration->save();
            }
        }

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Team is bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wedstrijd $wedstrijd, Team $team)
    {
        $this->authorize('delete', $team);

        if ($team->registrations()->count() > 0) {
            return redirect()->route('wedstrijden.show', $wedstrijd)->withErrors([
                'team' => 'Dit team kan niet verwijderd worden omdat er nog inschrijvingen aan gekoppeld zijn.'
            ]);
        }
        $team->delete();

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Team is verwijderd.');
    }

    public function registration_remove(Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('manage', $registration);

        $registration->team_id = null;
        $registration->save();

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Inschrijving is uit team verwijderd.');
    }

    public function registration_add(Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('manage', $registration);

        return view('pages.teams.registration_add', [
            'wedstrijd' => $wedstrijd,
            'registration' => $registration,
            'teams' => $wedstrijd->teams->where('niveau_id', $registration->niveau_id)->pluck('name', 'id'),
        ]);
    }

    public function registration_add_store(Request $request, Wedstrijd $wedstrijd, Registration $registration)
    {
        $this->authorize('manage', $registration);

        $this->validate($request, [
            'team_id' => 'required|exists:teams,id'
        ]);

        $registration->update($request->only('team_id'));

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Inschrijving is aan team toegevoegd.');
    }
}
