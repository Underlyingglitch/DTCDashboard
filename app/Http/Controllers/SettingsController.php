<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\PendingChange;
use App\Models\CalendarUpdate;
use App\Models\User;
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
            'enabled_change' => UserSetting::getValue('calendar_updates_enabled_change'),
            'new_districts' => UserSetting::getValue('calendar_updates_new_districts', []),
            'new_disciplines' => UserSetting::getValue('calendar_updates_new_disciplines', []),
            'change_districts' => UserSetting::getValue('calendar_updates_change_districts', []),
            'change_disciplines' => UserSetting::getValue('calendar_updates_change_disciplines', []),
        ];
        $subscriptions = Auth::user()->calendar_subscriptions;
        return view('pages.settings.calendar_updates', compact('settings', 'subscriptions'));
    }

    public function calendar_updates_post()
    {
        UserSetting::setValue('calendar_updates_enabled_new', request('enabled_new') == "on");
        UserSetting::setValue('calendar_updates_enabled_change', request('enabled_change') == "on");
        UserSetting::setValue('calendar_updates_new_districts', request('enabled_new') == "on" ? request('new_districts') : []);
        UserSetting::setValue('calendar_updates_new_disciplines', request('enabled_new') == "on" ? request('new_disciplines') : []);
        UserSetting::setValue('calendar_updates_change_districts', request('enabled_change') == "on" ? request('change_districts') : []);
        UserSetting::setValue('calendar_updates_change_disciplines', request('enabled_change') == "on" ? request('change_disciplines') : []);
        return redirect()->back()->with('success', 'Instellingen opgeslagen');
    }
}
