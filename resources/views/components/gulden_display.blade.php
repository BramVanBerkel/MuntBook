@props([
    'value',
    'colored' => false,
    'show_decimals' => true,
    'sign' => true,
])
@php
    $sign = $sign !== "false";

    $class = null;
    if($colored) {
        $class = match(true) {
            $value < 0 => 'text-danger',
            $value > 0 => 'text-success',
            default => '',
        };

    }

    //format the number to 8 decimal places
    $number_formatted = number_format($value, 8, ',', '.');

    //take the last 6 digits to display small
    $small = substr($number_formatted, -6);

    //and remove those 6 digits from the main value
    $number_formatted = substr_replace($number_formatted, '', -6);

    if($colored && $value > 0 && $sign) {
        $number_formatted = '+'.$number_formatted;
    }
@endphp
<span class="{{ $class }}">
    <span class="gulden-icon"></span> {{ $number_formatted }}<span class="small">{{ $small }}</span>
</span>
