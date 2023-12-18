<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public $disabled = false;
    public function __construct(
        public $name,
        public $label = null,
        public $placeholder = '--',
        public $value = null,
        public $options = [],
        $disabled = "no"
    ) {
        if ($disabled == "yes") {
            $this->disabled = true;
        }
        if ($disabled == "onvalue" && $value) {
            $this->disabled = true;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.select');
    }
}
