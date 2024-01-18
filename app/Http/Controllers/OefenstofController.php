<?php

namespace App\Http\Controllers;

use App\Models\DGResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class OefenstofController extends Controller
{
    public function index()
    {
        $last_updated = Setting::getValue('oefenstof_last_updated');
        $up_to_date = DGResource::where('category', 'Oefenstof en reglement')->where(function ($query) use ($last_updated) {
            $query->where('updated_at', '>', $last_updated)
                ->orWhere('created_at', '>', $last_updated);
        })->count() == 0;
        return view('pages.oefenstof.index', [
            'up_to_date' => $up_to_date,
        ]);
    }
}
