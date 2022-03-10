<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;

class AddressTransactionData
{
    public function __construct(
        public string $txid,
        public Carbon $timestamp,
        public float $amount,
    ) {}
}
