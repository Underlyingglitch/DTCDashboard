<?php

namespace App\Jobs\DGResources;

use PHPHtmlParser\Dom;
use Illuminate\Bus\Batch;
use App\Models\DGResource;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Jobs\DGResources\FetchResource;
use Illuminate\Support\Facades\Storage;
use App\Jobs\DGResources\ProcessUpdates;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateList implements ShouldQueue
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
        // Remove all documents from the storage that are not in the database
        $files = Storage::files('dg_resources');
        foreach ($files as $file) {
            $filename = explode('.', basename($file))[0];
            if (!DGResource::where('id', $filename)->exists()) {
                Storage::delete($file);
            }
        }

        // Retrieving DOCUMENTEN TURNEN HEREN
        $dom = new Dom;
        $dom->loadFromUrl('https://dutchgymnastics.nl/trainers-en-coaches/wedstrijdzaken/turnen-heren/documenten/');

        $jobs = [];

        foreach ($dom->find('.card') as $card) {
            $lis = $card->find('li.flex');
            $category = trim($card->find('h2')[0]->find('span')[0]->text);
            $urls = [];
            foreach ($lis as $li) {
                $a = $li->find('a')[0];
                $title = trim(preg_replace('/\(pdf,.*\)/', '', $a->text));
                $url = $a->getAttribute('href');
                if (preg_match('/\.pdf$/', $url)) {
                    $type = 'file';
                    $url = 'https://dutchgymnastics.nl' . $url;
                } elseif (preg_match('/youtube\.com/', $url)) {
                    $type = 'youtube';
                } else {
                    $type = 'url';
                }
                $urls[] = $url;
                $dg_resource = DGResource::updateOrCreate([
                    'name' => $title,
                ], [
                    'category' => $category,
                    'name' => $title,
                    'type' => $type,
                    'url' => $url,
                    'status' => 'idle'
                ]);
                $jobs[] = new FetchResource($dg_resource);
            }
            // Mark the resources in the database that are not in the list as deleted
            DGResource::where('category', $category)->whereNotIn('url', $urls)->update([
                'status' => 'deleted'
            ]);
        }
        Bus::batch($jobs)->finally(function (Batch $batch) {
            ProcessUpdates::dispatch();
        })->dispatch();
    }
}
