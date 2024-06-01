<?php

namespace App\Livewire\Jury;

use App\Models\Score;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Registration;
use App\Models\ScoreCorrection;

class RoundTable extends Component
{
    public $matchday;
    public $wedstrijd;
    public $toestel;
    public $current_round;
    public $groups;
    public $group_names = [];
    public $registrations;

    public function getListeners()
    {
        return [
            "echo:settings.current_round,.SettingUpdated" => 'updateRound',
            "echo:jury,.GroupUpdated" => "groupUpdated",
            "echo:jury,.RegistrationUpdated" => "registrationUpdated",
            "echo:jury,.ScoreUpdated" => "scoreSaved",
            "echo:jury,.ScoreCorrectionAdded" => "scoreCorrection",
            "echo:jury,.ScoreCorrectionUpdated" => "scoreCorrection",
        ];
    }

    public function scoreCorrection($data)
    {
        // dd($data);
        if ($data['score']['toestel'] != $this->toestel) return;
        $startnumber = $data['score']['startnumber'];
        foreach ($this->registrations as $index => $baan) {
            if (array_key_exists($startnumber, $baan)) {
                $registration = $this->registrations[$index][$startnumber];
                if (!isset($data['action'])) {
                    $registration['status'] = 'correction_pending';
                    $registration['score'] = $data['score']['total'];
                    $registration['new_score'] = $data['sc']['total'];
                    $this->registrations[$index][$startnumber] = $registration;
                    return;
                }

                if ($data['action'] == 'delete') {
                    $registration['status'] = 'scored';
                    $registration['new_score'] = $data['score']['total'];
                    $this->registrations[$index][$startnumber] = $registration;
                    $this->dispatch('notification', 'Score correctie', 'Score correctie voor ' . $startnumber . ' is afgewezen', 'error');
                    return;
                }
                if ($data['action'] == 'update' && $data['sc']['approved'] == 1) {
                    if ($data['sc']['d'] == 0) {
                        $registraion['status'] = 'pending';
                        $registration['score'] = null;
                        $registration['new_score'] = null;
                        $this->registrations[$index][$startnumber] = $registration;
                        $this->dispatch('notification', 'Score correctie', 'Score voor ' . $startnumber . ' is verwijderd', 'error');
                        return;
                    }
                    $registration['status'] = 'scored';
                    $registration['score'] = $data['score']['total'];
                    $this->registrations[$index][$startnumber] = $registration;
                    $this->dispatch('notification', 'Score correctie', 'Score correctie voor ' . $startnumber . ' is toegewezen', 'success');
                    return;
                }
            }
        }
        // $this->getRegistrations();
    }

    public function scoreSaved($data)
    {
        if ($data['toestel'] != $this->toestel) return;
        foreach ($this->registrations as $index => $baan) {
            if (array_key_exists($data['startnumber'], $baan)) {
                if ($data['dns']) {
                    $this->registrations[$index][$data['startnumber']]['status'] = 'dns';
                    $this->registrations[$index][$data['startnumber']]['score'] = null;
                    return;
                }
                $this->registrations[$index][$data['startnumber']]['status'] = 'scored';
                $this->registrations[$index][$data['startnumber']]['score'] = $data['score'];
                return;
            }
        }
        // $this->getRegistrations();
    }

    public function groupUpdated($data)
    {
        if (in_array($data['group'], $this->groups)) $this->getRegistrations();
    }

    public function registrationUpdated($data)
    {
        $startnumber = $data['registration']['startnumber'];
        foreach ($this->registrations as $index => $baan) {
            if (array_key_exists($startnumber, $baan)) {
                $this->getRegistrations();
            }
        }
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
        foreach ($this->groups as $index => $group) {
            if ($group != 0) {
                $baan = floor($group / 10) + 1;
                $this->group_names[$index] = 'Baan ' . $baan . ' Groep ' . $group % 10;
            }
        }
        $this->getRegistrations();
    }

    public function getRegistrations()
    {
        // $registrations = Wedstrijd::find(Setting::getValue('current_wedstrijd'))->registrations->whereIn('group_id', $this->groups);
        $registrations = Registration::where('match_day_id', $this->matchday)->whereIn('niveau_id', $this->wedstrijd->niveaus->pluck('id'))->whereIn('group_id', $this->groups)->with(['gymnast', 'club', 'niveau'])->get();
        $scores = Score::where('toestel', $this->toestel)->where('match_day_id', $this->matchday)->get();
        $score_corrections = ScoreCorrection::where('approved', 0)->whereHas('score', function ($query) {
            $query->where('toestel', $this->toestel)->where('match_day_id', $this->matchday);
        })->get();
        $this->registrations = [];
        foreach ($registrations as $registration) {
            $status = 'pending';
            if ($registration->signed_off == 1) $status = 'signed_off';
            if ($scores->where('startnumber', $registration->startnumber)->count() > 0) {
                if (is_null($scores->where('startnumber', $registration->startnumber)->first()->d)) {
                    $status = 'dns';
                } else {
                    $status = 'scored';
                    $score = $scores->where('startnumber', $registration->startnumber)->first();
                    if ($score_corrections->where('startnumber', $registration->startnumber)->count() > 0) {
                        $status = 'correction_pending';
                        $new_score = $score_corrections->where('startnumber', $registration->startnumber)->first()->total;
                    }
                }
            }
            $baan = floor($registration->group_id / 10);
            $this->registrations[$baan][$registration->startnumber] = [
                'id' => $registration->id,
                'startnumber' => $registration->startnumber,
                'name' => $registration->gymnast->name,
                'club' => $registration->club->name,
                'niveau' => $registration->niveau->niveau_number ? $registration->niveau->full_name . ' (' . $registration->niveau->niveau_number . ')' : $registration->niveau->full_name,
                'status' => $status,
                'score' => $score->total ?? null,
                'new_score' => $new_score ?? null,
            ];
        }
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
