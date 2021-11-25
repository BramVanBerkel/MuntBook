<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavItem extends Component
{
    public function __construct(
        public string $route,
        public ?array $children = []
    ) {
    }

    public function isActive(): bool
    {
        $routes = collect([
            $this->route,
            ...collect($this->children)->pluck('route')
        ]);
        return $routes->contains(url()->current());
    }

    public function render()
    {
        return view('components.nav-item');
    }
}
