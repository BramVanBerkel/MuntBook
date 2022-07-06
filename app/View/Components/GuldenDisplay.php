<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuldenDisplay extends Component
{
    public string $class = '';

    public string $integer = '';

    public string $fractional = '';

    public function __construct(float $value, bool $colored = false, bool $showSign = true, bool $short = false)
    {
        if ($colored) {
            $this->class = match (true) {
                $value < 0 => 'text-red-600',
                $value > 0 => 'text-green-600',
                default => '',
            };
        }

        //format the value to 8 decimal places
        $value = number_format($value, 8, ',', '.');

        if (! $short) {
            //take the last 6 digits to display the fractionals
            $this->fractional = substr($value, -6);
        }

        //and remove those 6 digits from the value
        $this->integer = substr_replace($value, '', -6);

        //add a plus if we want to show the sign, a - is automatically added
        if ($colored && $value > 0 && $showSign) {
            $this->integer = "+$this->integer";
        }
    }

    public function render()
    {
        return view('components.gulden-display');
    }
}
