<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\Wedstrijd;

class JuryTafelController extends Controller
{
    public function index()
    {
        $this->authorize('jurytafel');

        return view('pages.jurytafel.index');
    }

    public function toestel($toestel)
    {
        $this->authorize('jurytafel');

        if ($toestel < 1 || $toestel > 6) {
            abort(404);
        }
        $wedstrijd = Wedstrijd::find(Setting::getValue('current_wedstrijd'));
        return view('pages.jurytafel.toestel', compact('toestel', 'wedstrijd'));
    }
}
