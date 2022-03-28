<?php

namespace App\Enums;

use Carbon\Carbon;

enum PriceTimeframeEnum: string
{
    case ONE_DAY = '1d';
    case SEVEN_DAYS = '7d';
    case ONE_MONTH = '1m';
    case THREE_MONTHS = '3m';
    case ONE_YEAR = '1y';
    case YEAR_TO_DATE = 'ytd';
    case ALL = 'all';

    /**
     * Returns the size one tick on the graph should be in seconds.
     * @return int
     */
    public function tickSize(): int
    {
        return match($this) {
            self::ONE_DAY => 300, // 5 minutes
            self::SEVEN_DAYS => 600, // 10 minutes
            self::ONE_MONTH => 3600, // 1 hour
            self::THREE_MONTHS => 43200, // 12 hours
            self::ONE_YEAR, self::ALL => 86400, // 24 hours
            self::YEAR_TO_DATE => now()->startOfYear()->diffInSeconds(now()->startOfDay()) / 1440, // 60 ticks per day
        };
    }

    /**
     * Returns the date from which prices can be selected, or null if all prices can be selected.
     * @return Carbon|null
     */
    public function since(): ?Carbon
    {
        return match($this) {
            self::ONE_DAY => now()->subDay(),
            self::SEVEN_DAYS => now()->subDays(7),
            self::ONE_MONTH => now()->subMonth(),
            self::THREE_MONTHS => now()->subMonths(3),
            self::ONE_YEAR => now()->subYear(),
            self::YEAR_TO_DATE => now()->startOfYear(),
            self::ALL => null,
        };
    }
}
