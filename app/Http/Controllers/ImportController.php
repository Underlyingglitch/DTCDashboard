<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
use App\Models\Group;
use App\Models\Niveau;
use App\Models\Gymnast;
use App\Models\MatchDay;
use App\Models\Registration;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.import.index', [
            'type' => $request->has('matchday') ? "registrations" : null,
            'matchdays' => MatchDay::all()->pluck('name', 'id'),
            'matchday' => $request->input('matchday'),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);
        if ($request->type == 'registrations') {
            $this->validate($request, [
                'matchday' => 'required|exists:match_days,id',
            ]);
            return $this->import_from_file($request);
        } else if ($request->type == 'registrations_match') {
            $this->validate($request, [
                'import_matchday' => 'required|exists:match_days,id',
            ]);
            return $this->import_from_match_day($request);
        } else {
            return redirect()->back()->with('error', 'Onbekend type import');
        }
    }

    public function import_from_file(Request $request)
    {
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
            $matchday = MatchDay::find($request->matchday);
            $matchday->registrations()->delete();
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
                            'competition_id' => $matchday->competition_id,
                            'niveau_id' => $niveau->id
                        ],
                        [
                            'name' => $row[7],
                            'competition_id' => $matchday->competition_id,
                            'niveau_id' => $niveau->id
                        ]
                    );


                Club::updateOrCreate(
                    ['id' =>  $row[2]],
                    [
                        'id' => $row[2],
                        'name' => $row[3]
                    ]
                );
                Registration::updateOrCreate(
                    [
                        'match_day_id' => $matchday->id,
                        'gymnast_id' => $row[0]
                    ],
                    [
                        'gymnast_id' => $row[0],
                        'match_day_id' => $matchday->id,
                        'startnumber' => $i,
                        'club_id' => $row[2],
                        'niveau_id' => $niveau->id,
                        'group_id' => Group::where([['baan', $row[8]], ['nr', $row[9]]])->first()->id,
                        'team_id' => $team->id ?? null
                    ]
                );
            }
            return redirect()->back()->with('success', 'Registraties geimporteerd');
        }
    }
    public function import_from_match_day(Request $request)
    {
        $import_matchday = MatchDay::find($request->import_matchday);
        $registrations = $import_matchday->registrations;
        $matchday = MatchDay::find($request->matchday);
        // Copy all registrations from the import match day to the new match day but set signed_off to false
        foreach ($registrations as $registration) {
            $new_registration = $registration->replicate();
            $new_registration->match_day_id = $matchday->id;
            $new_registration->signed_off = false;
            $new_registration->save();
        }
        return redirect()->back()->with('success', 'Registraties geimporteerd');
    }
}
