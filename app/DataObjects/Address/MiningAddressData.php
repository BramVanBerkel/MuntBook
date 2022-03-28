<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;

class MiningAddressData
{
    public function __construct(
        public string $address,
        public int $firstBlock,
        public Carbon $firstBlockDate,
        public int $lastBlock,
        public Carbon $lastBlockDate,
        public int $totalRewards,
        public float $totalRewardsValue,
    ) {
    }
}
