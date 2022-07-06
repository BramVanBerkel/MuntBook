<?php

namespace App\DataObjects;

class CalendarItem
{
    public function __construct(
        public readonly string $date,
        public readonly int $count,
    ) {
    }
}
