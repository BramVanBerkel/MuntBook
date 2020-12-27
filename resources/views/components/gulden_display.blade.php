@props([
    'value',
    'colored' => false,
    'show_decimals' => true,
])
@php
    $class = null;
    if($colored) {
        $class = $value < 0 ? 'text-danger' : 'text-success';
    }

    //format the number to 8 decimal places
    $number_formatted = number_format($value, 8, ',');

    //take the last 6 digits to display small
    $small = substr($number_formatted, -6);

    //and remove those 6 digits from the main value
    $number_formatted = substr_replace($number_formatted, '', -6);

    if($colored && $value > 0) {
        $number_formatted = '+'.$number_formatted;
    }
@endphp
<div class="{{ $class }}">
    <span class="gulden-icon"></span> {{ $number_formatted }}<span class="small">{{ $small }}</span>
</div>

