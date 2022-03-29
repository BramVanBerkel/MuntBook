<?php

namespace App\DataObjects;

use Carbon\Carbon;

class AverageBlocktimeData
{
    public function __construct(
        public Carbon $date,
        public float $seconds,
    ) {}
}
