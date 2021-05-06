<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self MINUTE_1()
 * @method static self MINUTE_5()
 * @method static self MINUTE_15()
 * @method static self MINUTE_30()
 * @method static self HOUR_1()
 * @method static self HOUR_2()
 * @method static self HOUR_3()
 * @method static self HOUR_4()
 * @method static self DAY_1()
 * @method static self DAY_7()
 * @method static self DAY_30()
 */
final class PriceTimeframeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'MINUTE_1' => '1 minute',
            'MINUTE_5' => '5 minutes',
            'MINUTE_15' => '15 minutes',
            'MINUTE_30' => '30 minutes',
            'HOUR_1' => '1 hour',
            'HOUR_2' => '2 hour',
            'HOUR_3' => '3 hour',
            'HOUR_4' => '4 hour',
            'DAY_1' => '1 day',
            'DAY_7' => '1 week',
            'DAY_30' => '1 month',
        ];
    }
}
