<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Location;
use App\Models\MatchDay;
use App\Models\Competition;
use Illuminate\Http\Request;

class MatchDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Competition $competition)
    {
        // dd(Location::all()->pluck('name', 'id')->toArray());
        return view('pages.matchdays.create', [
            'competition' => $competition,
            'locations' => Location::all()->pluck('select_name', 'id')->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Competition $competition, Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        $competition->matchDays()->create($request->only('date', 'location_id'));

        return redirect()->route('competitions.show', $competition)->with('success', 'Wedstrijddag is aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MatchDay $matchday)
    {
        return view('pages.matchdays.show', [
            'matchday' => $matchday,
            'activeWedstrijd' => Setting::getValue('current_wedstrijd'),
            'wedstrijden' => $matchday->wedstrijden()->with('niveaus')->orderBy('index')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatchDay $matchday)
    {
        return view('pages.matchdays.edit', [
            'matchday' => $matchday,
            'locations' => Location::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatchDay $matchday)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        $matchday->update($request->only('date', 'location_id'));

        return redirect()->route('competitions.show', $matchday->competition_id)->with('success', 'Wedstrijddag is aangepast.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatchDay $matchday)
    {
        $competition_id = $matchday->competition_id;
        $matchday->delete();

        return redirect()->route('competitions.show', $competition_id)->with('success', 'Wedstrijddag is verwijderd.');
    }

    public function setactive(MatchDay $matchday)
    {
        Setting::setValue('current_match_day', $matchday->id);

        return redirect()->route('competitions.show', $matchday->competition_id);
    }
}
