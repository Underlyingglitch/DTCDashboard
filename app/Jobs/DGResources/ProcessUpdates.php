<?php

namespace App\Jobs\DGResources;

use App\Models\User;
use App\Models\DGResource;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Notifications\DGResourceUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessUpdates implements ShouldQueue
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
        $new = DGResource::where('status', 'new')->pluck('id');
        $hasupdate = DGResource::where('status', 'hasupdate')->pluck('id');
        $deleted = DGResource::where([['status', 'deleted'], ['updated_at', '>', Setting::getValue('dg_resources_last_update')]])->pluck('id');
        if ($new->count() + $hasupdate->count() + $deleted->count() == 0) {
            return;
        }
        Log::info('New: ' . $new->count());
        Log::info('Has update: ' . $hasupdate->count());
        Log::info('Deleted: ' . $deleted->count());
        // Notify users
        // Get all users that are subscribed to the DGResources
        $subscribed = Setting::withoutGlobalScope('user_id')->where([['key', 'dg_resources_subscribed'], ['value', 'on']])->pluck('user_id');
        $users = User::whereIn('id', $subscribed)->where('email_verified_at', '!=', null)->get();
        foreach ($users as $user) {
            $user->notify(new DGResourceUpdate($new, $hasupdate, $deleted));
        }
        // Set all new and hasupdate to idle
        DGResource::whereIn('id', $new)->orWhereIn('id', $hasupdate)->update(['status' => 'idle']);
        Setting::setValue('dg_resources_last_update', now()->toDateTimeString());
    }
}
