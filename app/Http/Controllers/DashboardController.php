<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (
            (env('APP_ENV') === 'local' || env('APP_ENV') === 'dev')
            && Device::where('device_id', $request->session()->get('device_id'))->where('type', 'jury')->exists()
        ) {
            return redirect()->route('jurytafel.index');
        }
        $settings = Setting::getValues([
            'current_competition',
            'current_match_day',
            'current_wedstrijd',
            'current_round'
        ]);
        return view('pages.dashboard.index', [
            'current_competition' => $settings->current_competition ? \App\Models\Competition::find($settings->current_competition) : null,
            'current_match_day' => $settings->current_match_day ? \App\Models\MatchDay::find($settings->current_match_day) : null,
            'current_wedstrijd' => $settings->current_wedstrijd ? \App\Models\Wedstrijd::find($settings->current_wedstrijd) : null,
            'current_round' => $settings->current_round,
        ]);
    }
}
