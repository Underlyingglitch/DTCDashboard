<?php

namespace App\Http\Controllers;

use Cache;
use App\Models\Device;
use App\Models\Setting;
use App\Models\SyncTask;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Scores\CalculateTeamScore;
use Session;

class InternalAPIController extends Controller
{
    public function ping(Request $request)
    {
        $device = Device::where('device_id', $request->device_id)->first();
        if ($device) {
            $device->last_seen = now();
            $device->loaded_page = $request->loaded_page;
            $device->save();
        }
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
                        $error_ids[] = $change['id'];
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
                        $data = json_decode($change['data'], true);
                        // If we are updating a wedstrijd, we need to json_decode the group_settings
                        if ($change['model_type'] == 'App\Models\Wedstrijd' && isset($data['group_settings'])) {
                            $data['group_settings'] = json_decode($data['group_settings'], true);
                        }
                        $model::find($change['model_id'])->update($data);
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
            CalculateTeamScore::dispatch($active_wedstrijd->match_day_id, $team->id, 0);
        }
        return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    }
}
