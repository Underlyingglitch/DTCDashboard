<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

class ExportDB implements ShouldQueue
{
    use Queueable;

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
        $tables = [
            'competitions',
            'trainers',
            'competition_trainer',
            'groups',
            'gymnasts',
            'teams',
            'juries',
            'locations',
            'match_days',
            'niveaus',
            'wedstrijds',
            'niveau_wedstrijd',
            'registrations',
            'scores',
            'score_corrections',
            'team_scores',
            'processed_scores',
            'user_settings',
        ];
        $data = [];
        foreach ($tables as $table) {
            $data[$table] = [];
            DB::table($table)->orderBy('id')->chunk(100, function ($rows) use ($table, &$data) {
                if ($table == 'user_settings') {
                    // Only get the rows where user_id is null
                    $rows = $rows->where('user_id', null);
                }
                foreach ($rows as $row) {
                    $data[$table][] = $row;
                }
            });
        }

        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.json';
        Storage::disk('local')->put($filename, json_encode($data));
        event(new \App\Events\DBExportReady($filename));
    }
}
