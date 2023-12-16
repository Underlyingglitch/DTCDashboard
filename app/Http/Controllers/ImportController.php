<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
use App\Models\Group;
use App\Models\Niveau;
use App\Models\Gymnast;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.import.index', [
            'type' => $request->has('competition') ? "registrations" : null,
            'competitions' => Competition::all()->pluck('name', 'id'),
            'competition' => $request->input('competition'),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'competition' => 'required_if:type,registrations|exists:competitions,id',
            'file' => 'required|file|mimes:xlsx',
        ]);
        $reader = new Xlsx();
        // 0 relatienummer deelnemer
        // 1 naam deelnemer
        // 2 relatienummer club
        // 3 naam club
        // 4 niveau
        // 5 supplement
        // 6 geboortedatum
        // 7 teamnaam
        // 8 baan
        // 9 groep
        // 10 beeldmateriaal
        $spreadsheet = $reader->load($request->file);
        $array = $spreadsheet->getSheet(0)->toArray();

        if ($request->type == 'registrations') {
            $competition = Competition::find($request->competition);

            foreach ($array as $i => $row) {
                if (!is_numeric($row[0])) {
                    continue;
                }
                Gymnast::updateOrCreate(
                    ['id' => $row[0]],
                    [
                        'id' => $row[0],
                        'name' => $row[1],
                        'birthdate' => $row[6],
                        'photo' => ($row[10] == "Ja" || $row[10] == "ja" || $row[10] == "JA" || $row[10] == "Y" || $row[10] == "y" || $row[10] == 1) ? 1 : 0
                    ]
                );

                $niveau = Niveau::updateOrCreate(
                    ['name' =>  $row[4], 'supplement' => $row[5]],
                    ['name' => $row[4], 'supplement' => $row[5]]
                );
                $team = (empty($row[7])) ? null
                    : Team::firstOrCreate(
                        [
                            'name' => $row[7],
                            'competition_id' => $competition->id,
                            'niveau_id' => $niveau->id
                        ],
                        [
                            'name' => $row[7],
                            'competition_id' => $competition->id,
                            'niveau_id' => $niveau->id
                        ]
                    );

                foreach ($competition->matchdays as $matchday) {
                    $matchday->registrations()->delete();
                    Registration::updateOrCreate(
                        [
                            'match_day_id' => $matchday->id,
                            'gymnast_id' => $row[0]
                        ],
                        [
                            'gymnast_id' => $row[0],
                            'match_day_id' => $matchday->id,
                            'startnumber' => $i,
                            'club_id' => Club::updateOrCreate(
                                ['id' =>  $row[2]],
                                [
                                    'id' => $row[2],
                                    'name' => $row[3]
                                ]
                            )->id,
                            'niveau_id' => $niveau->id,
                            'group_id' => Group::where([['baan', $row[8]], ['nr', $row[9]]])->first()->id,
                            'team_id' => $team->id ?? null
                        ]
                    );
                }
            }
            return redirect()->back()->with('success', 'Registraties geimporteerd');
        }
    }
}
