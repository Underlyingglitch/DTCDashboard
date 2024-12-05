<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ExportDBButton extends Component
{
    public $listeners = ['echo:internal,.DBExportReady' => 'download'];

    public function export()
    {
        $this->dispatch('notification', 'Database export', 'Houd deze pagina open', 'info');
        \App\Jobs\ExportDB::dispatch();
    }

    public function download($payload)
    {
        $this->dispatch('notification', 'Database export', 'Bestand downloaden', 'success');
        $filename = $payload['filename'];
        return response()->streamDownload(function () use ($filename) {
            echo Storage::disk('local')->get($filename);
        }, $filename);
        Storage::disk('local')->delete($filename);
    }

    public function render()
    {
        return view('livewire.export-db-button');
    }
}
