<?php

namespace App\Http\Controllers;

use App\Models\User;
use PHPHtmlParser\Dom;
use App\Models\Setting;
use App\Models\CalendarItem;
use App\Models\CalendarUpdate;
use App\Jobs\Calendar\SendUpdates;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Notifications\CalendarUpdateNotification;

class TestController extends Controller
{

    public function index(Request $request)
    {
        
    }
}
