<?php

namespace App\DataObjects;

class TransactionOutputsData
{
    public function __construct(
        public string $address,
        public float $amount,
    ) {
    }
}
