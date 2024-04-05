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
        $points = [50, 45, 40, 37, 34, 31, 28, 26, 24, 22, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1, 1];
        $place = null;
        dd($points[$place - 1]);
    }
}
