<?php

namespace App\Jobs\Calendar;

use App\Models\CalendarItem;
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

class GetEventUpdates implements ShouldQueue
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
        if (Cache::get('no_daily_updates', false)) return;
        // Cache::set('no_daily_updates', false, strtotime('tomorrow') - time());

        $events = CalendarItem::where('date_from', '<=', now())
            ->where('date_to', '>=', now()->startOfDay())
            ->whereHas('subscribers')
            ->get();

        Log::info('Found ' . $events->count() . ' events with subscribers for today');
        if ($events->count() == 0) {
            Cache::set('no_daily_updates', true, strtotime('tomorrow') - time());
            return;
        }

        foreach ($events as $event) {
            GetDetails::dispatch($event, false, true);
        }

        // $settings = Setting::withoutGlobalScopes()->where('key', 'LIKE', 'calendar_updates_%')->get();

        // $users = User::all();
        // foreach ($users as $user) {
        //     $usersettings = $settings->where('user_id', $user->id)->pluck('value', 'key')->toArray();
        //     $notifications = [];
        //     if (count($usersettings) == 0) continue;

        //     if ($usersettings['calendar_updates_enabled_new'] == "true") {
        //         $new_notifications = CalendarUpdate::with('calendar_item')->where('type', 'created')
        //             ->whereHas('calendar_item', function ($query) use ($usersettings) {
        //                 return $query->whereIn('district', json_decode($usersettings['calendar_updates_new_districts'], true));
        //             })
        //             ->whereHas('calendar_item', function ($query) use ($usersettings) {
        //                 return $query->whereIn('discipline', json_decode($usersettings['calendar_updates_new_disciplines'], true));
        //             })
        //             ->get()->toArray();
        //         $notifications = array_merge($notifications, $new_notifications);
        //     }

        //     if ($usersettings['calendar_updates_enabled_change'] == "true") {
        //         $new_notifications = CalendarUpdate::with('calendar_item')->where('type', 'updated')
        //             ->whereHas('calendar_item', function ($query) use ($usersettings) {
        //                 return $query->whereIn('district', json_decode($usersettings['calendar_updates_change_districts'], true));
        //             })
        //             ->whereHas('calendar_item', function ($query) use ($usersettings) {
        //                 return $query->whereIn('discipline', json_decode($usersettings['calendar_updates_change_disciplines'], true));
        //             })
        //             ->get()->toArray();
        //         $notifications = array_merge($notifications, $new_notifications);
        //     }

        //     $subscriptions = $user->calendar_subscriptions->pluck('id')->toArray();
        //     $sub_updates = CalendarUpdate::with('calendar_item')->whereIn('calendar_item_id', $subscriptions)->get()->toArray();
        //     $notifications = array_merge($notifications, $sub_updates);

        //     $notifications = array_map("unserialize", array_unique(array_map("serialize", $notifications)));

        //     if (count($notifications) > 0)
        //         $user->notify(new CalendarUpdateNotification($notifications));
        // }
    }
}
