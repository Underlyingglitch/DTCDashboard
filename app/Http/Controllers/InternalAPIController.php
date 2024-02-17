<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SyncTask;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use App\Jobs\Scores\CalculateTeamScore;
use Illuminate\Support\Facades\Bus;
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
                    }
                    break;
                case 'update':
                    // Update the model on the current database
                    try {
                        $model::find($change['model_id'])->update(json_decode($change['data'], true));
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
                    }
                    break;
                default:
                    Log::error('Unknown operation: ' . $change['operation']);
                    $error_ids[] = $change['id'];
                    break;
            }
        }
        // Recalculate team scores
        $active_wedstrijd = Wedstrijd::find(Setting::getValue('current_wedstrijd'));
        foreach ($active_wedstrijd->teams as $team) {
            $jobs = [];
            for ($i = 1; $i <= 6; $i++) {
                $jobs[] = new CalculateTeamScore($team, $i, $active_wedstrijd->match_day_id);
            }
            Bus::chain($jobs)->dispatch();
        }
        return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    }
}
