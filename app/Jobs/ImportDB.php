<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportDB implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($this->data as $table => $rows) {
            DB::table($table)->truncate();
            foreach ($rows as $row) {
                DB::table($table)->insert($row);
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        event(new \App\Events\DBImportReady());
    }
}
