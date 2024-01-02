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
        return view('pages.wedstrijden.show', [
            'wedstrijd' => $wedstrijd
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wedstrijd $wedstrijd)
    {
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
        $wedstrijd->delete();

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id)->with('success', 'Wedstrijd is verwijderd.');
    }

    public function move_group(Wedstrijd $wedstrijd, Registration $registration)
    {
        return view('pages.wedstrijden.move_group', [
            'wedstrijd' => $wedstrijd,
            'registration' => $registration
        ]);
    }

    public function move_group_store(Request $request, Wedstrijd $wedstrijd, Registration $registration)
    {
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
        $registration->update(['signed_off' => !$registration->signed_off]);

        return redirect()->route('wedstrijden.show', $wedstrijd)->with('success', 'Registratie is aangepast.');
    }

    public function setactive(Wedstrijd $wedstrijd)
    {
        Setting::setValue('current_wedstrijd', $wedstrijd->id);

        return redirect()->route('matchdays.show', $wedstrijd->match_day_id);
    }
}
