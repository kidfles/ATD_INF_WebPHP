<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class WhitelabelLayout extends Component
{
    public $company;

    /**
     * Create a new component instance.
     */
    public function __construct($company = null)
    {
        $this->company = $company;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.whitelabel');
    }
}
