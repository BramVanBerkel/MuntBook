<?php

namespace App\DataObjects;

use Spatie\DataTransferObject\DataTransferObject;

class BlockSubsidyData
{
    public function __construct(
        public float $mining,
        public float $witness,
        public float $development,
    ) {}

    public function total(): float|int
    {
        return $this->mining + $this->development + $this->witness;
    }
}
