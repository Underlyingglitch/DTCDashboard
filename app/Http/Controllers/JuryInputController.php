<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\MatchDay;

class JuryInputController extends Controller
{
    public function index()
    {
        return view('pages.juryinput.index');
    }

    public function toestel($toestel)
    {
        if ($toestel < 1 || $toestel > 6) {
            abort(404);
        }
        $matchday = MatchDay::find(Setting::getValue('current_match_day'));
        return view('pages.juryinput.toestel', compact('toestel', 'matchday'));
    }
}
