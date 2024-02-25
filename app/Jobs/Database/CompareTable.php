<?php

namespace App\Jobs\Database;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CompareTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $table, public $value)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Compare the table rows in both connections and return missing values or differences based on id
        $rows_local = \DB::connection()->table($this->table)->get();
        $rows_prod = \DB::connection('prod_server')->table($this->table)->get();
        $diff = [];
        $checked_ids = [];
        foreach ($rows_local as $row) {
            $checked_ids[] = $row->id;
            $row_prod = $rows_prod->where('id', $row->id)->first();
            if ($row_prod == null) {
                $diff[] = ['id' => $row->id, 'local' => $row, 'prod' => null];
            } elseif ($row != $row_prod) {
                $diff[] = ['id' => $row->id, 'local' => $row, 'prod' => $row_prod];
            }
        }
        foreach ($rows_prod->whereNotIn('id', $checked_ids) as $row) {
            $diff[] = ['id' => $row->id, 'local' => null, 'prod' => $row];
        }
        $this->value[2] = $diff;
        event(new \App\Events\Database\ComparedTable($this->table, $this->value));
    }
}
