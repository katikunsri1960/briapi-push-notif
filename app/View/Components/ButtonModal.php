<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonModal extends Component
{
    public $modalName;
    /**
     * Create a new component instance.
     */
    public function __construct($modalName)
    {
        $this->modalName = $modalName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-modal', [
            'modalName' => $this->modalName,
        ]);
    }
}
