<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Score;
use PHPHtmlParser\Dom;
use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\CalendarItem;
use Illuminate\Http\Request;
use App\Models\CalendarUpdate;
use App\Jobs\Calendar\SendUpdates;
use Illuminate\Support\Facades\Cache;
use App\Notifications\CalendarUpdateNotification;

class TestController extends Controller
{

    public function index(Request $request)
    {
        $items = [];
        foreach (MatchDay::all() as $matchday) {
            foreach ($matchday->registrations->groupBy('niveau') as $niveau => $registrations) {
                $items[] = [
                    'niveau' => $niveau,
                    'startnumber' => $registrations->first()->startnumber,
                    'matchday' => $matchday->id,
                ];
            }
        }
        foreach ($items as $item) {
            $scores = Score::where('match_day_id', $item['matchday'])
                ->where('startnumber', $item['startnumber'])->get();
            foreach ($scores as $score) {
                \App\Jobs\Scores\CalculateScorePlace::dispatch($score);
            }
        }
    }
}
