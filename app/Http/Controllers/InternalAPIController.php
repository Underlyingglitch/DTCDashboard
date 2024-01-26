<?php

namespace App\Http\Controllers;

use App\Models\SyncTask;
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

    public function changes(Request $request)
    {
        if (!isset($request->changes)) {
            return response()->json(['error' => 'No changes provided'], 400);
        }

        $success_ids = [];
        $error_ids = [];

        foreach ($request->changes as $change) {
            // Get the model
            $model = app($change['model_type']);
            // Switch on the event
            switch ($change['operation']) {
                case 'create':
                    if (SyncTask::find($change['id'])) {
                        // TODO: Compare the data and update if needed
                        $success_ids[] = $change['id'];
                        break;
                    }
                    // Create the model on the current database
                    try {
                        $model::create(json_decode($change['data'], true));
                        SyncTask::updateOrCreate(['id' => $change['id']], [
                            'id' => $change['id'],
                            'model_type' => $change['model_type'],
                            'model_id' => $change['model_id'],
                            'operation' => $change['operation'],
                            'data' => $change['data'],
                            'synced' => true
                        ]);
                        $success_ids[] = $change['id'];
                    } catch (\Throwable $th) {
                        $error_ids[] = $change['id'];
                        Log::error($th);
                    }
                    break;
                case 'update':
                    // Update the model on the current database
                    try {
                        $result = $model::find($change['model_id']);
                        Log::info($result);
                        $result->update(json_decode($change['data'], true));
                        SyncTask::updateOrCreate(['id' => $change['id']], [
                            'id' => $change['id'],
                            'model_type' => $change['model_type'],
                            'model_id' => $change['model_id'],
                            'operation' => $change['operation'],
                            'data' => $change['data'],
                            'synced' => true
                        ]);
                        $success_ids[] = $change['id'];
                    } catch (\Throwable $th) {
                        $error_ids[] = $change['id'];
                        Log::error($th);
                    }
                    break;
                default:
                    Log::error('Unknown operation: ' . $change['operation']);
                    $error_ids[] = $change['id'];
                    break;
            }
        }

        return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    }
}
