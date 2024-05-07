<?php

namespace App\Http\Controllers;

use App\Models\CalendarItem;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', CalendarItem::class);
        // Include status == deleted only if the GET parameter is set
        $calendar_items = CalendarItem::all();

        return view('pages.calendar.index', compact('calendar_items'));
    }
}
