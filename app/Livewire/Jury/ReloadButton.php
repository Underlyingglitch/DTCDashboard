<?php

namespace App\Livewire\Jury;

use Livewire\Component;

class ReloadButton extends Component
{
    public $page;

    public function getListeners()
    {
        return [
            "echo:jury,.ReloadPage" => 'reload',
        ];
    }

    public function mount($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.jury.reload-button');
    }

    public function reloadClicked()
    {
        if ($this->page) return $this->reload();
        event(new \App\Events\Jury\ReloadPage());
    }

    public function reload()
    {
        // Redirect to the same page
        if ($this->page) return $this->reload();
    }
}
