<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavItemChild extends Component
{
    public function __construct(
        public string $route
    ) {
    }

    public function render()
    {
        return view('components.nav-item-child');
    }
}
