<?php

namespace App\DataObjects;

use Spatie\DataTransferObject\DataTransferObject;

class TransactionOutputsData
{
    public function __construct(
        public string $address,
        public float $amount,
    ) {}
}
