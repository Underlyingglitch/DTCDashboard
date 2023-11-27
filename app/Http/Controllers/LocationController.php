<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.locations.index', [
            'locations' => Location::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required|string',
            'address' => 'required|string',
        ]);

        Location::create([
            'name'    => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('locations.index');
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
    public function edit(Location $location)
    {
        return view('pages.locations.edit', [
            'location' => $location,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $this->validate($request, [
            'name'    => 'required|string',
            'address' => 'required|string',
        ]);

        $location->update([
            'name'    => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('locations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index');
    }
}
