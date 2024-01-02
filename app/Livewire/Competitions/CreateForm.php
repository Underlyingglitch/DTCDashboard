<?php

namespace App\Livewire\Competitions;

use Livewire\Component;
use App\Models\Competition;

class CreateForm extends Component
{
    public $name;

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function save()
    {
        $this->validate();

        Competition::create(['name' => $this->name]);

        session()->flash('success', 'Competitie succesvol aangemaakt.');

        return $this->redirect(route('competitions.index'));
    }

    public function render()
    {
        return view('livewire.competitions.create-form');
    }
}
