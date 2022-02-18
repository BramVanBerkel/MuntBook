<?php

namespace App\DataObjects\Address;

use App\Enums\WitnessAddressPartStatusEnum;
use Spatie\DataTransferObject\DataTransferObject;

class WitnessAddressPartData extends DataTransferObject
{
    public float $value;

    public float $weight;

    public WitnessAddressPartStatusEnum $status;

    public int $blocksSinceLastActive;
}
