<?php

namespace App\DataObjects;

use Carbon\Carbon;

class PriceData
{
    public function __construct(
        public Carbon $timestamp,
        public float $value,
    ) {}
}
