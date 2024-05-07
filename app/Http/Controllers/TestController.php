<?php

namespace App\Http\Controllers;

use App\Models\User;
use PHPHtmlParser\Dom;
use App\Models\Setting;
use App\Models\CalendarItem;
use App\Models\CalendarUpdate;
use App\Jobs\Calendar\SendUpdates;
use Illuminate\Support\Facades\Cache;
use App\Notifications\CalendarUpdateNotification;

class TestController extends Controller
{
    public $notifications = [
        [
            "id" => 631,
            "calendar_item_id" => 1,
            "type" => "created",
            "value" => "[]",
            "created_at" => null,
            "updated_at" => null,
            "calendar_item" => [
                "id" => 1,
                "event_id" => 1805,
                "title" => "3e Plaatsingswedstrijd Eerste divisie Pre-Junioren, Junioren en Senioren",
                "discipline" => "Ritmische Gymnastiek",
                "district" => "Landelijk",
                "place" => "Deventer",
                "location_name" => "sportcomplex De Schegt",
                "location_address" => "Piet van Donkplein 1, 7422LW, Deventer",
                "date_from" => "2024-05-11T00:00:00.000000Z",
                "date_to" => null,
                "results" => null,
                "results_files" => [
                    "https://dutchgymnastics.nl/assets/Scores/Program/Wedstrijdboekje-11-mei.pdf"
                ],
                "program" => null,
                "program_files" => [],
                "description" => "organisatie: RGC Eleganza Utrecht en SVOD Deventer",
                "description_files" => [],
                "created_at" => "2024-05-05T10:09:38.000000Z",
                "updated_at" => "2024-05-05T10:51:50.000000Z"
            ]
        ],
        [
            "id" => 633,
            "calendar_item_id" => 85,
            "type" => "updated",
            "value" => '{"program_files":"[\"https:\\\/\\\/dutchgymnastics.nl\\\/assets\\\/Scores\\\/Program\\\/Sittard-25-05-2024-W1.pdf\",\"https:\\\/\\\/dutchgymnastics.nl\\\/assets\\\/Scores\\\/Program\\\/Sittard-25-05-2024-W2-correctie.pdf\",\"https:\\\/\\\/dutchgymnastics.nl\\\/assets\\\/Scores\\\/Program\\\/Sittard-25-05-2024-W3.pdf\",\"https:\\\/\\\/dutchgymnastics.nl\\\/assets\\\/Scores\\\/Program\\\/Sittard-25-05-2024-W4.pdf\"]"}',
            "created_at" => "2024-05-06T20:08:11.000000Z",
            "updated_at" => "2024-05-06T20:08:11.000000Z",
            "calendar_item" => [
                "id" => 85,
                "event_id" => 1697,
                "title" => "District Zuid - Districtsfinale 2024",
                "discipline" => "Turnen Heren",
                "district" => "Zuid",
                "place" => "Sittard",
                "location_name" => "Sittard",
                "location_address" => "Doctor Nolenslaan 128, 6136 GV, Sittard",
                "date_from" => "2024-05-25T00:00:00.000000Z",
                "date_to" => "2024-05-25T00:00:00.000000Z",
                "results" => null,
                "results_files" => [],
                "program" => null,
                "program_files" => [
                    "https://dutchgymnastics.nl/assets/Scores/Program/Sittard-25-05-2024-W1.pdf",
                    "https://dutchgymnastics.nl/assets/Scores/Program/Sittard-25-05-2024-W2-correctie.pdf",
                    "https://dutchgymnastics.nl/assets/Scores/Program/Sittard-25-05-2024-W3.pdf",
                    "https://dutchgymnastics.nl/assets/Scores/Program/Sittard-25-05-2024-W4.pdf"
                ],
                "description" => "some desc",
                "description_files" => [],
                "created_at" => "2024-05-05T10:57:46.000000Z",
                "updated_at" => "2024-05-06T20:08:11.000000Z"
            ]
        ]
    ];
    public $user_id = 1;

    public function index()
    {
        $user = User::find($this->user_id);
        $user->notify(new CalendarUpdateNotification($this->notifications));
    }
}
