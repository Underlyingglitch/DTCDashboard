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
use App\Models\Registration;
use Illuminate\Support\Facades\Cache;
use App\Notifications\CalendarUpdateNotification;

class TestController extends Controller
{

    public function index(Request $request)
    {

        // Set E score
        // $scores = Score::where('e', null)
        //     ->where('match_day_id', 10)
        //     ->whereNot('d', null)
        //     ->get();
        // foreach ($scores as $score) {
        //     $es = array_filter([$score->e1, $score->e2, $score->e3]);
        //     $score->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        //     $score->total = $score->d > 0 ? (($score->d + (10 - $score->e)) - $score->n) : 0;
        //     if ($score->total < 0) $score->total = 0;
        //     $score->save();
        //     // dd($score);
        // }
        // dd('done');

        //e is null and d is not null and match_day_id = 10



        // Recalculate places
        // $match_day_id = 10;
        // $niveau_id = 2;
        // $registrations = Registration::where('match_day_id', $match_day_id)
        //     ->where('niveau_id', $niveau_id)
        //     ->where('signed_off', false)
        //     ->with(['scores' => function ($query) use ($match_day_id) {
        //         $query->where('match_day_id', $match_day_id);
        //     }])
        //     ->get()
        //     ->sortByDesc(function ($registration) {
        //         return $registration->scores->sum('total');
        //     });
        // $previousScore = null;
        // $place = 0;
        // $same = 1;
        // foreach ($registrations as $registration) {
        //     if ($registration->scores->sum('total') != $previousScore) {
        //         $place += $same;
        //         $same = 1;
        //     } else {
        //         $same++;
        //     }
        //     $previousScore = $registration->scores->sum('total');
        //     $registration->place = $place;
        //     $registration->saveQuietly();
        // }
        // dd('done');




        // $items = [];
        // foreach (MatchDay::all() as $matchday) {
        //     foreach ($matchday->registrations->groupBy('niveau') as $niveau => $registrations) {
        //         $items[] = [
        //             'niveau' => $niveau,
        //             'startnumber' => $registrations->first()->startnumber,
        //             'matchday' => $matchday->id,
        //         ];
        //     }
        // }
        // foreach ($items as $item) {
        //     $scores = Score::where('match_day_id', $item['matchday'])
        //         ->where('startnumber', $item['startnumber'])->get();
        //     foreach ($scores as $score) {
        //         \App\Jobs\Scores\CalculateToestelRanking::dispatch($score);
        //     }
        // }
    }
}
