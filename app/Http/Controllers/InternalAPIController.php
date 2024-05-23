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
use App\Jobs\Scores\CalculateTeamScore;

class InternalAPIController extends Controller
{
    public function test(Request $request)
    {
        // Store the request to a file
        file_put_contents(storage_path('logs/internal_api.log'), json_encode($request->toArray()) . PHP_EOL, FILE_APPEND);
        foreach ($request['events'] as $event) {
            // if $event['channel'] starts with presence-jurytafel.
            if (strpos($event['channel'], 'presence-jurytafel.') === 0) {
                // Get the wedstrijd_id from the channel
                $toestel = explode('.', $event['channel'])[1];
                $key = 'monitor.jurytafel.' . $toestel;
                $count = Setting::getValue('jurytafel_count_' . $toestel, 0);
                if ($event['name'] == 'member_added') {
                    $count++;
                }
                if ($event['name'] == 'member_removed') {
                    // Only decrement if the count is higher than 0
                    if ($count > 0) $count--;
                }
                if ($event['name'] == 'channel_vacated') {
                    $count = 0;
                }
                Setting::setValue('jurytafel_count_' . $toestel, $count);
                event(new \App\Events\Monitor\JuryTafelPresenceChanged($toestel, Cache::get($key)));
            }
        }
    }

    public function ping(Request $request)
    {
        $device = Device::where('ip', $request->ip())->first();
        if ($device) {
            $device->loaded_page = $request->page;
            $device->authenticated_user_id = $request->user_id;
            $device->last_seen = now();
            $device->save();
            return response()->json(['message' => 'Saved', 'id' => $device->id, 'loaded_page' => $device->loaded_page, 'authenticated_user_id' => $device->authenticated_user_id]);
        }
        Log::error('Device not found: ' . $request->ip() . ' - ' . $request->page);
        return response()->json(['message' => 'Device not found'], 404);
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
                        if ($change['model_type'] == 'App\Models\Wedstrijd') {
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
            $jobs = [];
            for ($i = 1; $i <= 6; $i++) {
                $jobs[] = new CalculateTeamScore($team, $i, $active_wedstrijd->match_day_id);
            }
            Bus::chain($jobs)->dispatch();
        }
        return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    }
}
