<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;

class AddressData
{
    public function __construct(
        public string $address,
        public Carbon $firstSeen,
        public int $totalTransactions,
        public float $totalReceived,
        public float $totalSpent,
        public float $unspent,
    ) {
    }
}
