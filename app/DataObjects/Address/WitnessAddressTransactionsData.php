<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class WitnessAddressTransactionsData
{
    public function __construct(
        public int $height,
        public Carbon $timestamp,
        public float $reward,
        public float $compound,
    ) {}
}
