<?php

namespace App\Livewire;

use Livewire\Component;

class DatabaseMigrationBtn extends Component
{
    public function run()
    {
        $this->dispatch('notification', 'Database migratie', 'Houd deze pagina open', 'info');
        \Artisan::call('migrate');
        $this->dispatch('notification', 'Database migratie', 'Migratie voltooid', 'success');
    }

    public function render()
    {
        return view('livewire.database-migration-btn');
    }
}
