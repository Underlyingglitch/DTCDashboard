<?php

namespace App\Livewire\Competitions;

use Livewire\Component;
use App\Models\Competition;

class IndexTable extends Component
{

    public function delete(Competition $competition)
    {
        $this->authorize('delete', $competition);

        $competition->delete();

        session()->flash('success', 'Competitie succesvol verwijderd.');
    }

    public function render()
    {
        return view('livewire.competitions.index-table', [
            'competitions' => Competition::all(),
        ]);
    }
}
