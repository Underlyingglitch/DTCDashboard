<?php

namespace App\Jobs\Calendar;

use Carbon\Carbon;
use PHPHtmlParser\Dom;
use App\Models\CalendarItem;
use Illuminate\Bus\Queueable;
use App\Models\CalendarUpdate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private CalendarItem $calendar_item, private bool $created)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://dutchgymnastics.nl/wedstrijden-en-uitslagen/event', [
            'form_params' => [
                'id' => $this->calendar_item->event_id,
            ]
        ]);

        // Get the body of the response
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $calendar_item = CalendarItem::where([
            'event_id' => $this->calendar_item->event_id
        ])->first();

        $calendar_item->location_name = $data['locationName'];
        $address = array_filter([trim(trim($data['street']) . ' ' . trim($data['houseNumber'])), trim($data['postcode']), trim($data['city'])]);
        $calendar_item->location_address = implode(', ', $address);
        $calendar_item->description = $data['description'];
        $calendar_item->program = $data['program'];
        $calendar_item->results = $data['result'];

        if (!$this->created) {
            if ($calendar_item->isDirty()) {
                $update = CalendarUpdate::where([
                    'calendar_item_id' => $calendar_item->id
                ])->first();
                if ($update === null) {
                    CalendarUpdate::create([
                        'calendar_item_id' => $calendar_item->id,
                        'type' => 'updated',
                        'value' => json_encode($calendar_item->getDirty())
                    ]);
                } else {
                    $value = json_decode($update->value, true);
                    $value = array_merge($value, $calendar_item->getDirty());
                    $update->value = json_encode($value);
                    $update->save();
                }
            }
        }
        $calendar_item->save();

        Cache::decrement('calendar_jobs');
        if (Cache::get('calendar_jobs') <= 0) {
            CollectUpdates::dispatch();
            Cache::forget('calendar_jobs');
        }
    }
}
