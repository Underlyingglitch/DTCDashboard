<?php

namespace App\Livewire\Monitor;

use Cache;
use App\Models\User;
use App\Models\Device;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class UserSelect extends Component
{
    public $device_id;
    public $authenticated_user_id;
    public $users = [
        0 => 'Geen',
        1 => 'Jury 1',
        2 => 'Jury 2',
        3 => 'Jury 3',
        4 => 'Jury 4',
        5 => 'Jury 5',
        6 => 'Jury 6',
    ];

    public function getListeners()
    {
        return [
            "echo:monitor." . $this->device_id . ",.DeviceUpdated" => 'updateDevice',
        ];
    }

    public function mount($device)
    {
        $this->device_id = $device->id;
    }

    public function updateDevice($data)
    {
        $this->authenticated_user_id = $data['authenticated_user_id'];
    }

    public function setUser()
    {
        if ($this->authenticated_user_id == 0) {
            $this->authenticated_user_id = null;
            return;
        }
        $device = Device::find($this->device_id);
        $user = User::where('name', 'Jury ' . $this->authenticated_user_id)->first();
        $device->update(['authenticated_user_id' => $user->id ?? null, 'loaded_page' => '/login_as']);
    }

    public function render()
    {
        return view('livewire.monitor.user_select');
    }
}
