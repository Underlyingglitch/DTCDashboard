<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Score;
use App\Models\TeamScore;
use App\Models\Registration;


class TestController extends Controller
{
    public function index()
    {
        $registration = Registration::where('signed_off', false)->whereNull('place')->first();
        if ($registration === null) {
            dd('No more registrations to process');
        }
        \App\Jobs\Scores\CalculatePlace::dispatch($registration);
    }
}
