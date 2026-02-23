<?php

namespace App\Console\Commands;

use App\Jobs\Scores\CalculateTeamScore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class RecalculateScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'score:recalculate {match_day_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all scores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $match_day_id = $this->argument('match_day_id');
        $match_days = \App\Models\MatchDay::all();
        if ($match_day_id) {
            $match_days = $match_days->where('id', $match_day_id);
        }

        $batches = [];

        foreach ($match_days as $match_day) {
            $jobs = [];
            $this->info("Recalculating scores for: " . $match_day->full_name);
            foreach ($match_day->niveaus as $niveau) {
                for ($toestel = 1; $toestel <= 6; $toestel++) {
                    $jobs[] = new \App\Jobs\Scores\CalculateToestelRanking($match_day->id, $niveau->id, $toestel);
                }
            }

            $this->info("Recalculating ranking for: " . $match_day->full_name);
            foreach ($match_day->niveaus as $niveau) {
                $jobs[] = new \App\Jobs\Scores\CalculateRanking($match_day->id, $niveau->id);
            }

            $this->info("Recalculating team scores for: " . $match_day->full_name);
            foreach ($match_day->teams as $team) {
                $jobs[] = new \App\Jobs\Scores\CalculateTeamScore($match_day->id, $team->id, 0);
            }

            $this->info("Dispatching " . count($jobs) . " jobs...");
            $batch = Bus::batch($jobs)
                ->name("Recalculate - {$match_day->full_name}")
                ->allowFailures()
                ->onQueue('default')
                ->finally(function ($batch) use ($match_day) {
                    \Log::info("Completed recalculation for: {$match_day->full_name}");
                })
                ->catch(function ($batch, \Throwable $e) use ($match_day) {
                    \Log::error("Batch failed for {$match_day->full_name}: " . $e->getMessage());
                })
                ->dispatch();

            $batches[] = $batch;
        }

        $this->info("Waiting for batches to complete...");
        foreach ($batches as $batch) {
            while (!$batch->fresh()->finished()) {
                sleep(1);
                $this->line("Batch {$batch->id}: {$batch->fresh()->processedJobs()}/{$batch->fresh()->totalJobs} processed");
            }
        }
        $this->info("All batches completed!");
    }
}
