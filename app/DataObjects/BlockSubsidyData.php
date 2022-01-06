<?php

namespace App\DataObjects;

use Spatie\DataTransferObject\DataTransferObject;

class BlockSubsidyData
{
    public function __construct(
        public float|int $mining,
        public float|int $witness,
        public float|int $development,
    ) {}

    public function total(): float|int
    {
        return $this->mining + $this->development + $this->witness;
    }
}
