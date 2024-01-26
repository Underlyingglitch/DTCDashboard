<?php

namespace App\Jobs;

use Hamcrest\Core\Set;
use App\Models\Setting;
use App\Models\SyncTask;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncDatabase implements ShouldQueue
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
        if (Setting::getValue('sync_enabled') !== 'true') {
            return;
        }

        event(new \App\Events\DataSync\UpdateSyncStatus(2));

        $changes = SyncTask::where('synced', false)->get(['id', 'model_type', 'model_id', 'operation', 'data'])->toArray();

        if (count($changes) === 0) {
            return;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', config('app.api_base_url') . '/internal/changes', [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-KEY' => env('API_KEY')
            ],
            'body' => json_encode(['changes' => $changes]),
        ]);

        $data = json_decode($response->getBody(), true);
        SyncTask::whereIn('id', $data['success'])->update(['synced' => true]);
        if (count($data['error']) > 0) {
            Log::error("Failed syncs: " . implode(', ', $data['error']));
            event(new \App\Events\DataSync\UpdateSyncStatus(4, count($data['error'])));
        } else {
            event(new \App\Events\DataSync\UpdateSyncStatus(3));
        }
    }
}
