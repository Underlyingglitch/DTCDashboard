<?php

namespace App\Http\Controllers;

use Cache;
use App\Models\Device;
use App\Models\Setting;
use App\Models\SyncTask;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Break_;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Facades\Auditor;
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

    // public function audits(Request $request)
    // {
    //     if (!isset($request->audits)) {
    //         return response()->json(['error' => 'No audits provided'], 400);
    //     }
    //     $success_ids = [];
    //     $error_ids = [];
    //     foreach ($request->audits as $audit) {
    //         // Get the model
    //         $model = $audit['auditable_type'];
    //         // Switch on the event
    //         switch ($audit['event']) {
    //             case 'created':
    //                 // Create the model on the current database
    //                 if ($model::create($audit['new_values'])) {
    //                     $success_ids[] = $audit['id'];
    //                 } else {
    //                     $error_ids[] = $audit['id'];
    //                 }
    //                 break;
    //             case 'updated':
    //                 // Update the model on the current database
    //                 if ($model::find($audit['auditable_id'])->update($audit['new_values'])) {
    //                     $success_ids[] = $audit['id'];
    //                 } else {
    //                     $error_ids[] = $audit['id'];
    //                 }
    //                 break;
    //             case 'deleted':
    //                 // Delete the model on the current database
    //                 if ($model::find($audit['auditable_id'])->delete()) {
    //                     $success_ids[] = $audit['id'];
    //                 } else {
    //                     $error_ids[] = $audit['id'];
    //                 }
    //                 break;
    //             case 'restored':
    //                 // Restore the model on the current database
    //                 if ($model::withTrashed()->find($audit['auditable_id'])->restore()) {
    //                     $success_ids[] = $audit['id'];
    //                 } else {
    //                     $error_ids[] = $audit['id'];
    //                 }
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }
    //     return response()->json(['success' => $success_ids, 'error' => $error_ids]);
    // }

    public function ping(Request $request)
    {
        $device = Device::where('ip', $request->ip())->first();
        if ($device) {
            $device->loaded_page = $request->page;
            $device->last_seen = now();
            $device->save();
            return response()->json(['message' => 'Saved', 'id' => $device->id, 'loaded_page' => $device->loaded_page]);
        }
        Log::info('Device not found: ' . $request->ip() . ' - ' . $request->page);
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
                    // case 'setting':
                    //     $data = json_decode($change['data'], true);
                    //     Log::info('Setting ' . $data[0] . ' to ' . $data[1] . ' from internal API');
                    //     Setting::setValue($data[0], $data[1]);
                    //     $success_ids[] = $change['id'];
                    //     break;
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
