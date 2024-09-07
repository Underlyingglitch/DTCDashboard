<?php

namespace App\Jobs\Calendar;

use Cache;
use App\Models\CalendarItem;
use Illuminate\Bus\Queueable;
use App\Models\CalendarUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $month, private $year, private $key, private $value)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://dutchgymnastics.nl/wedstrijden-en-uitslagen/events', [
            'form_params' => [
                'sortField' => 'From',
                'sortDirection' => 'ASC',
                'year' => $this->year,
                'month' => $this->month,
                'region' => $this->key,
            ]
        ]);

        // Get the body of the response
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        $ids = [];
        foreach ($data as $item) {
            $ids[] = $item['id'];
            $event = [];
            $event['event_id'] = $item['id'];
            $event['title'] = $item['name'];
            $event['discipline'] = trim($item['disciplines'][0]['name'] ?? null);
            $event['district'] = $this->value;
            $event['place'] = $item['city'];
            $event['date_from'] = $item['dateFrom'];
            $event['date_to'] = $item['dateTo'];

            $event['results_files'] = $item['resultLocations'];
            $event['program_files'] = $item['programLocations'];
            $event['description_files'] = $item['descriptionLocations'];

            $calendar_item = CalendarItem::where([
                'event_id' => $event['event_id']
            ])->first();
            $created = false;
            if ($calendar_item === null) {
                $calendar_item = new CalendarItem;
                $calendar_item->fill($event);
                $calendar_item->save();
                CalendarUpdate::create([
                    'calendar_item_id' => $calendar_item->id,
                    'type' => 'created'
                ]);
                $created = true;
            } else {
                $calendar_item->fill($event);

                if ($calendar_item->isDirty()) {
                    CalendarUpdate::create([
                        'calendar_item_id' => $calendar_item->id,
                        'type' => 'updated',
                        'value' => json_encode($calendar_item->getDirty())
                    ]);
                }
                $calendar_item->save();
            }
            // TODO - Only dispatch if the event is within timeframe or subscribed to
            Cache::increment('calendar_jobs');
            GetDetails::dispatch($calendar_item, $created);
        }

        CalendarItem::whereNotIn('event_id', $ids)
            ->where('district', $this->value)
            ->whereMonth('date_from', $this->month)
            ->delete();

        Cache::decrement('calendar_jobs');
    }
}
