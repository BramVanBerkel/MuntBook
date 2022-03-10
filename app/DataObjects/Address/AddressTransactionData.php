<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class AddressTransactionData
{
    public function __construct(
        public string $txid,
        public Carbon $timestamp,
        public float $amount,
    ) {}
}
