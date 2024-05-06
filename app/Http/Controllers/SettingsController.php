<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\PendingChange;
use App\Models\CalendarUpdate;
use App\Models\UserSetting;
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
            event(new \App\Events\DataSync\UpdateSyncStatus($value));
        }

        return redirect()->back()->with('success', 'Instellingen opgeslagen');
    }

    public function database()
    {
        return view('pages.settings.database');
    }

    public function compare_databases()
    {
        if (config('app.compare_database') == false) {
            abort(403);
        }
        // Don't check for these tables
        $exlusions = [
            'migrations',
            'audits',
            'sessions',
            'password_reset_tokens',
            'personal_access_tokens',
            'jobs',
            'sync_tasks',
            'job_batches',
            'failed_jobs',
            'pending_changes',
            'model_has_permissions',
            'model_has_roles',
        ];
        // Get all tables in database for the main connection and the prod_server connection
        $tables_local = \DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $tables_prod = \DB::connection('prod_server')->getDoctrineSchemaManager()->listTableNames();
        // Result is an array with key = table name and value [boolean, boolean] where the first boolean is whether the table exists in the main connection and the second boolean is whether the table exists in the prod_server connection
        $tables = [];
        foreach ($tables_local as $table) {
            $tables[$table] = [true, false];
        }
        foreach ($tables_prod as $table) {
            if (array_key_exists($table, $tables)) {
                $tables[$table][1] = true;
            } else {
                $tables[$table] = [false, true];
            }
        }
        foreach ($tables as $table => $value) {
            if (in_array($table, $exlusions)) {
                unset($tables[$table]);
                continue;
            }
        }

        return view('pages.settings.compare_databases', ['tables' => $tables]);
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

    public function calendar_updates()
    {
        $settings = [
            'enabled_new' => UserSetting::getValue('calendar_updates_enabled_new'),
        ];
        return view('pages.settings.calendar_updates', compact('settings'));
    }

    public function calendar_updates_post()
    {
        UserSetting::setValue('calendar_updates_enabled_new', request('enabled_new') == "on");
        return redirect()->back()->with('success', 'Instellingen opgeslagen');
    }
}
