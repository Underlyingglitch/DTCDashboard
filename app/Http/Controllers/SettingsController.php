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

    public function compare_databases()
    {
        // Don't check for these tables
        $exlusions = ['migrations', 'audits', 'sessions', 'password_resets', 'jobs', 'failed_jobs', 'pending_changes'];
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
            // if ($value[0] && $value[1]) {
            //     // Compare the table rows in both connections and return missing values or differences based on id
            //     $rows_local = \DB::connection()->table($table)->get();
            //     $rows_prod = \DB::connection('prod_server')->table($table)->get();
            //     $diff = [];
            //     $checked_ids = [];
            //     foreach ($rows_local as $row) {
            //         $checked_ids[] = $row->id;
            //         $row_prod = $rows_prod->where('id', $row->id)->first();
            //         if ($row_prod == null) {
            //             $diff[] = ['id' => $row->id, 'local' => $row, 'prod' => null];
            //         } elseif ($row != $row_prod) {
            //             $diff[] = ['id' => $row->id, 'local' => $row, 'prod' => $row_prod];
            //         }
            //     }
            //     foreach ($rows_prod->whereNotIn('id', $checked_ids) as $row) {
            //         $diff[] = ['id' => $row->id, 'local' => null, 'prod' => $row];
            //     }
            //     $tables[$table][2] = $diff;
            //     break;
            // }
        }
        dd($tables);
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
