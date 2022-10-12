<?php

namespace App\DataObjects;

use Carbon\Carbon;

class AverageHashrateData
{
    public function __construct(
        public readonly Carbon $date,
        public readonly float $hashrate,
        public readonly float $difficulty,
    ) {
    }
}
