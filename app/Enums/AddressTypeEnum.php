<?php


namespace App\Enums;


use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ADDRESS()
 * @method static self WITNESS()
 * @method static self MINING()
 */
class AddressTypeEnum extends Enum
{
    protected static function values(): Closure
    {
        return function(string $name): string {
            return mb_strtolower($name);
        };
    }
}
