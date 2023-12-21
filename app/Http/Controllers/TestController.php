<?php

namespace App\Http\Controllers;

use App\Jobs\CalculateTeamScore;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $w = Wedstrijd::find(1);
        $teams = [];
        foreach ($w->teams as $team) {
            $toestel_scores = [0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < 6; $i++) {
                $toestel_scores[$i] = $team->registrations->pluck('scores')->flatten()->where('match_day_id', 1)->where('toestel', $i+1)->where('counted', true)->sum('total');
            }
            $teams[$team->id] = $toestel_scores;
        }
        dd($teams);
    }
}
