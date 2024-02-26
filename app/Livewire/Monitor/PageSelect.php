<?php

namespace App\Livewire\Monitor;

use Cache;
use App\Models\Device;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PageSelect extends Component
{
    public $device_id;
    public $selected_page;
    public $pages;

    private $allpages = [
        'jury' => [
            '/jurytafel' => 'Jury index',
            '/jurytafel/1' => 'Vloer',
            '/jurytafel/2' => 'Voltige',
            '/jurytafel/3' => 'Ringen',
            '/jurytafel/4' => 'Sprong',
            '/jurytafel/5' => 'Brug',
            '/jurytafel/6' => 'Rekstok',
            '/login' => 'Inloggen',
            '/logout' => 'Uitloggen',
            '/lock' => 'Vergrendelen',
        ]
    ];

    public function getListeners()
    {
        return [
            "echo:monitor." . $this->device_id . ",.DeviceUpdated" => 'updateDevice',
        ];
    }

    public function mount($device, $type)
    {
        $this->device_id = $device->id;
        $this->selected_page = $device->loaded_page;
        $this->pages = $this->allpages[$type];
    }

    public function updateDevice($data)
    {
        $this->selected_page = $data['loaded_page'];
    }

    public function setPage()
    {
        $pages = $this->allpages[Device::find($this->device_id)->type];
        if (!array_key_exists($this->selected_page, $pages)) {
            Log::error('Invalid page selected: ' . $this->selected_page);
            return;
        }
        Device::find($this->device_id)->update(['loaded_page' => $this->selected_page]);
    }

    public function render()
    {
        return view('livewire.monitor.page_select');
    }
}
