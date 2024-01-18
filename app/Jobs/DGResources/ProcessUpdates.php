<?php

namespace App\Jobs\DGResources;

use App\Models\DGResource;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
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
        $deleted = DGResource::where('status', 'deleted')->pluck('id');
        Log::info('New: ' . $new->count());
        Log::info('Has update: ' . $hasupdate->count());
        Log::info('Deleted: ' . $deleted->count());
        // Set all new and hasupdate to idle
        DGResource::whereIn('id', $new)->orWhereIn('id', $hasupdate)->update(['status' => 'idle']);
    }
}
