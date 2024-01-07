<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Facades\Auditor;

class InternalAPIController extends Controller
{
    public function audits(Request $request)
    {
        if (!isset($request->audits)) {
            return response()->json(['error' => 'No audits provided'], 400);
        }
        $success_ids = [];
        $error_ids = [];
        foreach ($request->audits as $audit) {
            // Get the model
            $model = $audit['auditable_type'];
            // Switch on the event
            switch ($audit['event']) {
                case 'created':
                    // Create the model on the current database
                    if ($model::create($audit['new_values'])) {
                        $success_ids[] = $audit['id'];
                    } else {
                        $error_ids[] = $audit['id'];
                    }
                    break;
                case 'updated':
                    // Update the model on the current database
                    if ($model::find($audit['auditable_id'])->update($audit['new_values'])) {
                        $success_ids[] = $audit['id'];
                    } else {
                        $error_ids[] = $audit['id'];
                    }
                    break;
                case 'deleted':
                    // Delete the model on the current database
                    if ($model::find($audit['auditable_id'])->delete()) {
                        $success_ids[] = $audit['id'];
                    } else {
                        $error_ids[] = $audit['id'];
                    }
                    break;
                case 'restored':
                    // Restore the model on the current database
                    if ($model::withTrashed()->find($audit['auditable_id'])->restore()) {
                        $success_ids[] = $audit['id'];
                    } else {
                        $error_ids[] = $audit['id'];
                    }
                    break;
                default:
                    break;
            }
        }
        return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    }
}
