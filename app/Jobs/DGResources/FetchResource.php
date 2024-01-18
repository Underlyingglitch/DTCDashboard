<?php

namespace App\Jobs\DGResources;

use App\Models\DGResource;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FetchResource implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public DGResource $dg_resource)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dg_resource = $this->dg_resource;
        if ($dg_resource->status === 'deleted') {
            Log::info('Resource ' . $dg_resource->id . ' is deleted');
            return;
        }
        if ($dg_resource->type !== 'file') {
            return;
        }

        $url = $dg_resource->url;
        // Create the storage path if it does not exist
        if (!file_exists(storage_path('app/public/dg_resources'))) {
            mkdir(storage_path('app/public/dg_resources'), 0777, true);
        }
        $path = storage_path('app/public/dg_resources/' . $dg_resource->id . '.pdf');
        $pdf = file_get_contents($url);
        file_put_contents($path, $pdf);
        $hash = md5_file($path);

        if ($dg_resource->status === 'new') {
            $dg_resource->old_hash = $hash;
            $dg_resource->save();
            return;
        }

        if ($dg_resource->old_hash === $hash) {
            $dg_resource->status = 'idle';
            $dg_resource->save();
            return;
        }

        $dg_resource->old_hash = $hash;
        $dg_resource->status = 'hasupdate';
        $dg_resource->save();
    }
}
