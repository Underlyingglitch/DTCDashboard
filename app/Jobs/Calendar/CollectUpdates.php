<?php

namespace App\Jobs\Calendar;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use App\Models\CalendarUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\CalendarUpdateNotification;

class CollectUpdates implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Cache::has('calendar_jobs') && Cache::get('calendar_jobs') != 0) {
            Log::info('Waiting for jobs to finish!');
            return;
        }
        if (Cache::has('has_updated')) {
            Log::info('Already sent updates!');
            return;
        }
        Log::info('Sending updates!');
        Cache::set('has_updated', true, now()->addMinutes(15));

        $settings = Setting::withoutGlobalScopes()->where('key', 'LIKE', 'calendar_updates_%')->get();

        $users = User::all();
        foreach ($users as $user) {
            $usersettings = $settings->where('user_id', $user->id)->pluck('value', 'key')->toArray();
            $notifications = [];
            if (count($usersettings) == 0) continue;

            if ($usersettings['calendar_updates_enabled_new'] == "true") {
                $new_notifications = CalendarUpdate::with('calendar_item')->where('type', 'created')
                    ->when($usersettings['calendar_updates_new_districts'] != "[]", function ($query) use ($usersettings) {
                        return $query->whereHas('calendar_item', function ($query) use ($usersettings) {
                            return $query->whereIn('district', json_decode($usersettings['calendar_updates_new_districts'], true));
                        });
                    })
                    ->when($usersettings['calendar_updates_new_disciplines'] != "[]", function ($query) use ($usersettings) {
                        return $query->whereHas('calendar_item', function ($query) use ($usersettings) {
                            return $query->whereIn('discipline', json_decode($usersettings['calendar_updates_new_disciplines'], true));
                        });
                    })
                    ->get()->toArray();
                $notifications = array_merge($notifications, $new_notifications);
            }

            if ($usersettings['calendar_updates_enabled_change'] == "true") {
                $new_notifications = CalendarUpdate::with('calendar_item')->where('type', 'updated')
                    ->when($usersettings['calendar_updates_change_districts'] != "[]", function ($query) use ($usersettings) {
                        return $query->whereHas('calendar_item', function ($query) use ($usersettings) {
                            return $query->whereIn('district', json_decode($usersettings['calendar_updates_change_districts'], true));
                        });
                    })
                    ->when($usersettings['calendar_updates_change_disciplines'] != "[]", function ($query) use ($usersettings) {
                        return $query->whereHas('calendar_item', function ($query) use ($usersettings) {
                            return $query->whereIn('discipline', json_decode($usersettings['calendar_updates_change_disciplines'], true));
                        });
                    })
                    ->get()->toArray();
                $notifications = array_merge($notifications, $new_notifications);
            }

            $subscriptions = $user->calendar_subscriptions->pluck('id')->toArray();
            $sub_updates = CalendarUpdate::with('calendar_item')->whereIn('calendar_item_id', $subscriptions)->get()->toArray();
            $notifications = array_merge($notifications, $sub_updates);

            $notifications = array_map("unserialize", array_unique(array_map("serialize", $notifications)));

            // SendUpdates::dispatch($user->id, $notifications);

            $user->notify(new CalendarUpdateNotification($notifications));
        }
    }
}
