<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;

class MiningAddressTransactionData
{
    public function __construct(
        public int $height,
        public Carbon $date,
        public float $reward,
        public int $difficulty,
    ) {
    }
}
