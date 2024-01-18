<?php

namespace App\Http\Controllers;

use App\Models\DGResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function download($dg_resource)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        }

        $this->authorize('view', $dg_resource);

        return response()->file(Storage::path('dg_resources/' . $dg_resource . '.pdf'));
    }
}
