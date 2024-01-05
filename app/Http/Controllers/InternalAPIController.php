<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InternalAPIController extends Controller
{
    public function audits(Request $request)
    {
        $return_ids = [];
        foreach ($request->audits as $audit) {
            $return_ids[] = $audit['id'];
        }
        return response()->json($return_ids);
    }
}
