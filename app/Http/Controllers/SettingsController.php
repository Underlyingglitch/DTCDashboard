<?php

namespace App\Http\Controllers;

use App\Models\PendingChange;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('view', Setting::class);
        return view('pages.settings.index');
    }

    public function set($setting, $value)
    {
        $this->authorize('update', Setting::class);

        Setting::setValue($setting, $value);

        // If the setting is the sync_enabled setting, and the value is true, also send an event
        if ($setting == 'sync_enabled') {
            event(new \App\Events\DataSync\UpdateSyncStatus($value == 'true' ? 1 : 0));
        }

        return redirect()->back()->with('success', 'Instellingen opgeslagen');
    }

    public function database()
    {
        return view('pages.settings.database');
    }

    public function database_process()
    {
        $this->authorize('process_database', Setting::class);

        foreach (PendingChange::all() as $change) {
            $model = app($change->model_type);
            if ($change->operation == 'create') {
                $model->create(json_decode($change->data, true));
            } elseif ($change->operation == 'update') {
                $model = $model->find($change->model_id);
                if ($model == null) {
                    continue;
                }
                // If model in database was updated after the pending change was created, skip this change
                if ($model->updated_at > $change->created_at) {
                    continue;
                }
                $model->update(json_decode($change->data, true));
            }
            $change->delete();
        }
        $count = PendingChange::count();
        if ($count > 0) {
            $message = ["warning", "Database bijgewerkt, maar er " . ($count > 1 ? 'zijn' : 'is') . " nog " . $count . " " . ($count > 1 ? 'wijzigingen' : 'wijziging') . " in de wachtrij"];
        } else {
            $message = ["success", "Database bijgewerkt"];
        }
        return redirect()->back()->with($message[0], $message[1]);
    }
}
