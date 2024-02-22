<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Location;
use App\Models\Competition;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.competitions.index', [
            'competitions' => Competition::orderBy('id', 'desc')->with('matchDays')->get(),
            'activeCompetition' => Setting::getValue('current_competition'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Competition::class);

        return view('pages.competitions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Competition::class);

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        Competition::create($request->only('name'));
        if (Setting::getValue('db_write') == 'off') {
            $message = ['warning', 'Competitie toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Competitie succesvol aangemaakt'];
        }
        return redirect()->route('competitions.index')->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Competition $competition)
    {
        $this->authorize('view', $competition);

        return view('pages.competitions.show', [
            'competition' => $competition,
            'activeMatchDay' => Setting::getValue('current_match_day'),
            'matchdays' => $competition->matchDays()->with('location')->orderBy('date')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competition $competition)
    {
        $this->authorize('update', Competition::class);

        return view('pages.competitions.edit', [
            'competition' => $competition,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competition $competition)
    {
        $this->authorize('update', Competition::class);

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $competition->update($request->only('name'));
        if (Setting::getValue('db_write') == 'off') {
            $message = ['warning', 'Competitie wijziging toegevoegd aan wachtrij'];
        } else {
            $message = ['success', 'Competitie succesvol bijgewerkt'];
        }
        return redirect()->route('competitions.index')->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competition $competition)
    {
        $this->authorize('delete', Competition::class);

        $competition->delete();

        return redirect()->route('competitions.index');
    }

    public function process_doorstroom(Competition $competition, Request $request)
    {
        $this->authorize('processDoorstroom', $competition);

        $this->validate($request, [
            'match_days' => 'required|array|min:1',
        ]);
        $match_days = [];
        foreach ($request->match_days as $match_day) {
            $split = explode('.', $match_day);
            $match_day = $split[0];
            $type = $split[1];
            if (array_key_exists($match_day, $match_days)) {
                return redirect()->back()->with('warning', 'Selecteer 1 type per wedstrijddag');
            }
            $match_days[$match_day] = $type;
        }

        dd($match_days);
    }
}
