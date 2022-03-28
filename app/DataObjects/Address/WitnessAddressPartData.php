<?php

namespace App\DataObjects\Address;

use App\Enums\WitnessAddressPartStatusEnum;

class WitnessAddressPartData
{
    public function __construct(
        public float $value,
        public float $weight,
        public WitnessAddressPartStatusEnum $status,
        public int $blocksSinceLastActive,
    ) {
    }
}
