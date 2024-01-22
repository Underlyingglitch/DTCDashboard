<?php

namespace App\Http\Controllers;

use App\Models\MatchDay;
use Illuminate\Http\Request;

class MatchDaysExportController extends Controller
{
    public function select(MatchDay $matchday, Request $request)
    {
        $this->validate($request, [
            'option' => 'required|not_in:--',
        ]);
        return redirect()->route('matchdays.export.' . $request->option, $matchday);
    }

    public function diplomas(MatchDay $matchday)
    {
        // Create empty xlsx and fill with export data
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Voornaam');
        $sheet->setCellValue('B1', 'Achternaam');
        $sheet->setCellValue('C1', 'Vereniging');
        $sheet->setCellValue('D1', 'Landelijk/district/regio');
        $sheet->setCellValue('E1', 'Voorwedstrijd');
        $sheet->setCellValue('F1', 'Niveau');
        $sheet->setCellValue('G1', 'Leeftijdscategorie');
        $sheet->setCellValue('H1', 'Team');
        $sheet->setCellValue('I1', 'Teamresultaat');
        $sheet->setCellValue('J1', 'Plaats');
        $sheet->setCellValue('K1', 'Datum');

        $registrations = $matchday->registrations()->with('gymnast', 'club', 'niveau', 'team')->get();
        foreach ($registrations as $row => $registration) {
            $sheet->setCellValue('A' . $row + 2, $registration->gymnast->first_name);
            $sheet->setCellValue('B' . $row + 2, $registration->gymnast->last_name);
            $sheet->setCellValue('C' . $row + 2, $registration->club->name);
            $sheet->setCellValue('D' . $row + 2, 'District Zuid');
            $sheet->setCellValue('E' . $row + 2, $matchday->competition->name);
            $sheet->setCellValue('F' . $row + 2, $registration->niveau->full_name);
            $sheet->setCellValue('G' . $row + 2, $registration->niveau->age_category);
            $sheet->setCellValue('H' . $row + 2, $registration->team ? $registration->team->name : '');

            $sheet->setCellValue('J' . $row + 2, $matchday->location->name);
            $sheet->setCellValue('K' . $row + 2, $matchday->date);
        }

        // Create the excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Create export folder if it doesn't exist
        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0777, true);
        }
        // Store and download the file
        $writer->save(storage_path('app/public/exports/diplomas.xlsx'));
        return response()->download(storage_path('app/public/exports/diplomas.xlsx'), 'Diplomas ' . $matchday->location->name . ' ' . $matchday->date . '.xlsx');
    }

    public function trainer_emails(MatchDay $matchday)
    {
        // Create empty xlsx and fill with export data
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Naam');
        $sheet->setCellValue('B1', 'Vereniging');
        $sheet->setCellValue('C1', 'Vereniging email');
        $sheet->setCellValue('D1', 'Trainer email');
        $sheet->setCellValue('E1', 'Telefoonnummer');

        $trainers = $matchday->competition->trainers()->with('club')->get();
        foreach ($trainers as $row => $trainer) {
            $sheet->setCellValue('A' . $row + 2, $trainer->name);
            $sheet->setCellValue('B' . $row + 2, $trainer->club->name);
            $sheet->setCellValue('C' . $row + 2, $trainer->club->email);
            $sheet->setCellValue('D' . $row + 2, $trainer->email);
            $sheet->setCellValue('E' . $row + 2, $trainer->phone);
        }

        // Create the excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Create export folder if it doesn't exist
        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0777, true);
        }
        // Store and download the file
        $writer->save(storage_path('app/public/exports/trainer_emails.xlsx'));
        return response()->download(storage_path('app/public/exports/trainer_emails.xlsx'), 'Emailadressen trainers ' . $matchday->location->name . ' ' . $matchday->date . '.xlsx');
    }
}
