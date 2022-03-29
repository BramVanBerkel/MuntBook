<?php

namespace App\DataObjects;

use Carbon\Carbon;

class BlocksPerDayData
{
    public function __construct(
        public Carbon $date,
        public int $blocks,
    ) {
    }
}
