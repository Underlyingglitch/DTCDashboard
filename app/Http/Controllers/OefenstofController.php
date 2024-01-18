<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OefenstofController extends Controller
{
    public function index()
    {
        return view('pages.oefenstof.index');
    }
}
