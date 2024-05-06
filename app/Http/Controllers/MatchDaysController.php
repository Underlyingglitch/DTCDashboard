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
        $this->authorize('create', MatchDay::class);
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
        $this->authorize('create', MatchDay::class);

        $this->validate($request, [
            'name' => 'required|string',
            'date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        $competition->match_days()->create($request->only('name', 'date', 'location_id'));
        if (!Setting::getValue('db_write_enabled')) {
            $message = ['warning', 'Wedstrijddag toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Wedstrijddag succesvol aangemaakt'];
        }
        return redirect()->route('competitions.show', $competition)->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MatchDay $matchday)
    {
        $this->authorize('view', $matchday);

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
        $this->authorize('update', $matchday);

        return view('pages.matchdays.edit', [
            'matchday' => $matchday,
            'locations' => Location::all()->pluck('select_name', 'id')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatchDay $matchday)
    {
        $this->authorize('update', $matchday);

        $this->validate($request, [
            'name' => 'required|string',
            'date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        $matchday->update($request->only('name', 'date', 'location_id'));

        if (!Setting::getValue('db_write_enabled')) {
            $message = ['warning', 'Wedstrijddag wijziging toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Wedstrijddag succesvol bijgewerkt'];
        }

        return redirect()->route('competitions.show', $matchday->competition_id)->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatchDay $matchday)
    {
        $this->authorize('delete', $matchday);

        $competition_id = $matchday->competition_id;
        $matchday->delete();

        return redirect()->route('competitions.show', $competition_id)->with('success', 'Wedstrijddag is verwijderd.');
    }
}
