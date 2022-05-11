<?php

namespace App\DataObjects;

class BlockSubsidyData
{
    public function __construct(
        public float  $mining,
        public float $witness,
        public float $development,
    ) {
    }

    public function total(): float
    {
        return $this->mining + $this->development + $this->witness;
    }

    public function mining(): float
    {
        return $this->mining;
    }

    public function witness(): float
    {
        return $this->witness;
    }

    public function development(): float
    {
        return $this->development;
    }
}
