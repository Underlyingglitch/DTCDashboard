<?php

namespace App\Console\Commands;

use App\Models\ProcessedScore;
use App\Models\Score;
use Illuminate\Console\Command;

class RefreshProcessesScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'score:refresh-processes-scores {wedstrijd_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh processed scores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wedstrijd = \App\Models\Wedstrijd::find($this->argument('wedstrijd_id'));

        $groups = $wedstrijd->groups->get();
        $wedstrijd_registrations = $wedstrijd->registrations()->get();
        foreach ($groups as $group) {
            for ($toestel = 1; $toestel <= 6; $toestel++) {
                $registrations = $wedstrijd_registrations->where('group_id', $group->id)->where('signed_off', 0)->pluck('startnumber');
                $score_count = Score::where('match_day_id', $wedstrijd->match_day_id)->whereIn('startnumber', $registrations)->where('toestel', $toestel)->count();

                if ($score_count == count($registrations)) {
                    ProcessedScore::updateOrCreate([
                        'wedstrijd_id' => $wedstrijd->id,
                        'group_id' => $group->id,
                        'toestel' => $toestel,
                    ], [
                        'completed' => 1,
                    ]);
                } else if ($score_count > 0) {
                    ProcessedScore::updateOrCreate([
                        'wedstrijd_id' => $wedstrijd->id,
                        'group_id' => $group->id,
                        'toestel' => $toestel,
                    ], [
                        'completed' => 0,
                    ]);
                } else {
                    $ps = ProcessedScore::where([
                        ['wedstrijd_id', $wedstrijd->id],
                        ['group_id', $group->id],
                        ['toestel', $toestel],
                    ])->first();
                    if ($ps) {
                        if (env('DO_BROADCASTING', true)) event(new \App\Events\ProcessedScoreUpdated($ps, true));
                        $ps->delete();
                    }
                }
            }
        }
        return true;
    }
}
