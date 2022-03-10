<?php

namespace App\DataObjects;

use Carbon\Carbon;

class BlocksOverviewData
{
    public function __construct(
        public int $height,
        public Carbon $timestamp,
        public int $transactions,
        public float $value,
    ) {}
}
