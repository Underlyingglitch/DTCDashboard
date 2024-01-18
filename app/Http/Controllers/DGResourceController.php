<?php

namespace App\Http\Controllers;

use App\Models\DGResource;
use Illuminate\Http\Request;

class DGResourceController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', DGResource::class);
        // Include status == deleted only if the GET parameter is set
        $dg_resources = DGResource::all();
        if (!request()->has('show_deleted')) {
            $dg_resources = $dg_resources->where('status', '!=', 'deleted');
        }

        return view('pages.dg_resources.index', [
            'dg_resources' => $dg_resources->groupBy('category'),
        ]);
    }
}
