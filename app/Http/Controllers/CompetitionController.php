<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.competitions.index', [
            'competitions' => Competition::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.competitions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        Competition::create($request->only('name'));

        return redirect()->route('competitions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Competition $competition)
    {
        return view('pages.competitions.show', [
            'competition' => $competition,
            'matchdays' => $competition->matchDays()->with('location')->orderBy('date')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competition $competition)
    {
        return view('pages.competitions.edit', [
            'competition' => $competition,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competition $competition)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $competition->update($request->only('name'));

        return redirect()->route('competitions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competition $competition)
    {
        $competition->delete();

        return redirect()->route('competitions.index');
    }
}
