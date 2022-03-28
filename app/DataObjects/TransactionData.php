<?php

namespace App\DataObjects;

use Carbon\Carbon;

class TransactionData
{
    public function __construct(
        public string $txid,
        public int $height,
        public Carbon $timestamp,
        public float $amount,
        public int $version,
        public ?string $rewardedWitnessAddress,
        public string $type,
    ) {
    }
}
