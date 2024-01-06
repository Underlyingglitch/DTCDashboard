<?php

namespace App\Jobs;

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
        $audits = Audit::where('synced', false)->get(['id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values'])->toArray();
        // Send to API
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env('API_BASE_URL') . '/audits', [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-KEY' => env('API_KEY')
            ],
            'body' => json_encode(['audits' => $audits]),
        ]);
        // Update synced based on response
        Log::info($response->getBody());
    }
}
