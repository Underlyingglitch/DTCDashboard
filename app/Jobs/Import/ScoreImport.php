<?php

namespace App\Jobs\Import;

use App\Models\MatchDay;
use App\Models\Registration;
use App\Models\Score;
use App\Models\TeamScore;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class ScoreImport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    private User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(public int $user_id, public string $file_path, public int $match_day_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user = \App\Models\User::find($this->user_id);
        $this->user->notify(
            new \App\Notifications\UserNotification(
                'Importeren scores gestart',
                'De import van scores is gestart. Je ontvangt een notificatie wanneer deze klaar is.',
                'info'
            )
        );

        $data = $this->readData();

        // dd($array);
        $matchday = MatchDay::find($this->match_day_id);

        // Clear existing scores for the match day
        Score::where('match_day_id', $matchday->id)->delete();
        TeamScore::where('match_day_id', $matchday->id)->delete();

        $this->importScores($data);

        foreach ($matchday->wedstrijden as $wedstrijd) {
            Artisan::call('score:refresh-processes-scores', [
                'wedstrijd_id' => $wedstrijd->id
            ]);
        }
        Artisan::call('score:recalculate', ['match_day_id' => $matchday->id]);

        $this->user->notify(
            new \App\Notifications\UserNotification(
                'Importeren scores voltooid',
                'De import van scores is voltooid.',
                'success'
            )
        );

        Storage::delete($this->file_path);
    }

    public function failed()
    {
        $this->user->notify(
            new \App\Notifications\UserNotification(
                'Importeren scores mislukt',
                'Er is een fout opgetreden tijdens het importeren van de scores. Probeer het opnieuw of neem contact op met de support.',
                'error'
            )
        );
    }


    private function readData()
    {
        $reader = new Csv();
        $path = Storage::path($this->file_path);
        $csv = $reader->load($path);
        return $csv->getSheet(0)->toArray();
    }

    private function importScores($data)
    {
        $scoresToInsert = [];
        $signoffUpdates = [];

        foreach ($data as $i => $row) {
            if (!is_numeric($row[0])) {
                continue;
            }
            $startnumber = $row[8];

            // Check signoff state
            if ($row[14] == "J") {
                $signoffUpdates[] = $startnumber;
                continue; // Skip importing scores for signed off registrations
            }

            $toestel_offsets = [19, 33, 47, 61, 90, 104];
            for ($j = 0; $j < 6; $j++) {
                $toestel = $j + 1;
                $d = $row[$toestel_offsets[$j] + 0];
                $e1 = $row[$toestel_offsets[$j] + 2];
                $e2 = $row[$toestel_offsets[$j] + 3];
                $e3 = $row[$toestel_offsets[$j] + 4];
                $n = $row[$toestel_offsets[$j] + 9];
                $b = $row[$toestel_offsets[$j] + 8];
                $totaal = $row[$toestel_offsets[$j] + 11];

                // Create a temporary Score instance to calculate total
                $tempScore = new Score();
                $tempScore->fill([
                    'd' => is_numeric($d) ? floatval($d) : null,
                    'e1' => is_numeric($e1) ? floatval($e1) : null,
                    'e2' => is_numeric($e2) ? floatval($e2) : null,
                    'e3' => is_numeric($e3) ? floatval($e3) : null,
                    'n' => is_numeric($n) ? floatval($n) : null,
                    'b' => is_numeric($b) ? floatval($b) : null,
                ]);
                $calculatedTotal = $tempScore->calculateTotal();

                // Validate total
                $expectedTotal = round(floatval($totaal), 3);
                $actualTotal = round(floatval($calculatedTotal), 3);
                if ($expectedTotal !== $actualTotal) {
                    $this->user->notify(
                        new \App\Notifications\UserNotification(
                            'Importeren scores - Validatiefout',
                            "Er is een validatiefout gevonden bij startnummer $startnumber, toestel $toestel. Verwachte totaal: $expectedTotal, berekende totaal: $actualTotal. De import is gestopt.",
                            'error'
                        )
                    );
                    return; // Stop processing if validation fails
                }

                $scoresToInsert[] = [
                    'match_day_id' => $this->match_day_id,
                    'startnumber' => $startnumber,
                    'toestel' => $toestel,
                    'd' => is_numeric($d) ? floatval($d) : null,
                    'e1' => is_numeric($e1) ? floatval($e1) : null,
                    'e2' => is_numeric($e2) ? floatval($e2) : null,
                    'e3' => is_numeric($e3) ? floatval($e3) : null,
                    'n' => is_numeric($n) ? floatval($n) : null,
                    'b' => is_numeric($b) ? floatval($b) : null,
                    'total' => $calculatedTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert all scores at once (bypasses observers)
        if (!empty($scoresToInsert)) {
            Score::insert($scoresToInsert);
        }

        // Bulk update signoffs
        if (!empty($signoffUpdates)) {
            Registration::where('match_day_id', $this->match_day_id)
                ->whereIn('startnumber', $signoffUpdates)
                ->update(['signed_off' => true]);
        }
    }
}
