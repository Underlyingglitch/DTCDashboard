<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Score;
use App\Models\TeamScore;
use App\Models\Registration;


class TestController extends Controller
{
    public $registration;
    public function index()
    {
        $this->registration = Registration::find(1228);
        $registrations = Registration::where('match_day_id', $this->registration->match_day_id)
            ->where('niveau_id', $this->registration->niveau_id)
            ->where('signed_off', false)
            ->with(['scores' => function ($query) {
                $query->where('match_day_id', $this->registration->match_day_id);
            }])
            ->get()
            ->sortByDesc(function ($registration) {
                return $registration->scores->sum('total');
            });
        $previousScore = null;
        $place = 0;
        foreach ($registrations as $registration) {
            if ($previousScore !== $registration->scores->sum('total')) {
                $place++;
            }
            $registration->place = $place;
            $registration->saveQuietly();
            $previousScore = $registration->scores->sum('total');
        }
    }
}
