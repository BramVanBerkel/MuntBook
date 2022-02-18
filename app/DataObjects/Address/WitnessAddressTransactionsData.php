<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class WitnessAddressTransactionsData extends DataTransferObject
{
    public int $height;

    public Carbon $timestamp;

    public float $reward;

    public float $compound;
}
