<?php

namespace App\Http\Controllers;

use App\Models\MatchDay;
use Illuminate\Http\Request;

class LiveScoresController extends Controller
{
    public function index()
    {
        $matchdays = MatchDay::orderBy('date', 'desc')->with(['location', 'competition'])->get();
        return view('pages.livescores.index', [
            'matchdays' => $matchdays,
        ]);
    }
}
