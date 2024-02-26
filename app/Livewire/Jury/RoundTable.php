<?php

namespace App\Livewire\Jury;

use App\Models\Score;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Registration;

class RoundTable extends Component
{
    public $matchday;
    public $wedstrijd;
    public $toestel;
    public $current_round;
    public $groups;
    public $group_names;
    public $registrations;

    public function getListeners()
    {
        return [
            "echo:settings.current_round,.SettingUpdated" => 'updateRound',
            "echo:jury,.GroupUpdated" => "groupUpdated",
            "echo:jury,.ScoreUpdated" => "scoreSaved",
        ];
    }

    public function scoreSaved($data)
    {
        foreach ($this->registrations as $index => $baan) {
            if (array_key_exists($data['startnumber'], $baan)) {
                $this->registrations[$index][$data['startnumber']]['status'] = 'scored';
                $this->registrations[$index][$data['startnumber']]['score'] = $data['score'];
                return;
            }
        }
        $this->getRegistrations();
    }

    public function groupUpdated($data)
    {
        if (in_array($data['group'], $this->groups)) $this->getRegistrations();
    }

    public function updateRound($data)
    {
        $this->current_round = $data['value'];
        $this->getGroups();
    }

    public function mount($toestel, $wedstrijd)
    {
        $this->matchday = Setting::getValue('current_match_day');
        $this->current_round = Setting::getValue('current_round');
        $this->toestel = $toestel;
        $this->wedstrijd = $wedstrijd;
        $this->getGroups();
    }

    public function getGroups()
    {
        $index = array_search($this->toestel, explode('-', $this->wedstrijd->round_settings));
        $this->groups = $this->wedstrijd->group_settings[1][$this->current_round - 1][$index];
        $this->group_names = [];
        array_map(function ($group) {
            $baan = floor($group / 10) + 1;
            $this->group_names[] = 'Baan ' . $baan . ' Groep ' . $group % 10;
        }, $this->groups);
        $this->getRegistrations();
    }

    public function getRegistrations()
    {
        // $registrations = Wedstrijd::find(Setting::getValue('current_wedstrijd'))->registrations->whereIn('group_id', $this->groups);
        $registrations = Registration::where('match_day_id', $this->matchday)->whereIn('niveau_id', $this->wedstrijd->niveaus->pluck('id'))->whereIn('group_id', $this->groups)->with(['gymnast', 'club', 'niveau'])->get();
        $scores = Score::where('toestel', $this->toestel)->where('match_day_id', $this->matchday)->get();
        $this->registrations = [];
        foreach ($registrations as $registration) {
            $status = 'pending';
            if ($registration->signed_off == 1) $status = 'signed_off';
            if ($scores->where('startnumber', $registration->startnumber)->count() > 0) $status = 'scored';
            $baan = floor($registration->group_id / 10);
            $this->registrations[$baan][$registration->startnumber] = [
                'id' => $registration->id,
                'startnumber' => $registration->startnumber,
                'name' => $registration->gymnast->name,
                'club' => $registration->club->name,
                'niveau' => $registration->niveau->niveau_number ? $registration->niveau->full_name . ' (' . $registration->niveau->niveau_number . ')' : $registration->niveau->full_name,
                'status' => $status,
                'score' => $scores->where('startnumber', $registration->startnumber)->first()->total ?? null,
            ];
        }
        // dd($this->registrations);
    }

    public function clicked($sn)
    {
        $this->dispatch('sn_clicked', sn: $sn);
    }

    public function render()
    {
        return view('livewire.jury.round-table');
    }
}
