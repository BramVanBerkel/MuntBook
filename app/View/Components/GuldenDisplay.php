<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuldenDisplay extends Component
{
    public string $class = "";
    public string $integer = "";
    public string $fractional = "";

    public function __construct(float $value, bool $colored = false, bool $showSign = true)
    {
        if($colored) {
            $this->class = match(true) {
                $value < 0 => 'text-danger',
                $value > 0 => 'text-success',
                default => '',
            };
        }

        //format the value to 8 decimal places
        $value = number_format($value, 8, ',', '.');

        //take the last 6 digits to display the fractionals
        $this->fractional = substr($value, -6);

        //and remove those 6 digits from the value
        $this->integer = substr_replace($value, '', -6);

        if($colored && $value > 0 && $showSign) {
            $this->integer = "+$this->integer";
        }
    }
    
    public function render()
    {
        return view('components.gulden-display');
    }
}
