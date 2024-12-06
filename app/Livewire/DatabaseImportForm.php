<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class DatabaseImportForm extends Component
{
    use WithFileUploads;
    public $file;

    public $listeners = [
        'fileUploadFinished' => 'handleFileUpload',
        'echo:internal,.DBImportReady' => 'done'
    ];

    public function done()
    {
        // File upload finished, you can now process the file
        $this->dispatch('notification', 'Database import', 'Importeren voltooid', 'success');
        $this->file = null;
    }

    public function import()
    {
        if ($this->file === null) {
            $this->dispatch('notification', 'Database import', 'Geen bestand geselecteerd', 'error');
            return;
        }
        // Get JSON content from the uploaded file
        $data = json_decode(file_get_contents($this->file->getRealPath()), true);
        if ($data === null || !is_array($data)) {
            $this->dispatch('notification', 'Database import', 'Ongeldig bestand', 'error');
            return;
        }
        $this->dispatch('notification', 'Database import', 'Houd deze pagina open', 'info');
        \App\Jobs\ImportDB::dispatch($data);
    }

    public function render()
    {
        return view('livewire.database-import-form');
    }
}
