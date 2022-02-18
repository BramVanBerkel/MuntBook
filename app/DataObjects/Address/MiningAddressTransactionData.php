<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class MiningAddressTransactionData extends DataTransferObject
{
    public int $height;

    public Carbon $date;

    public float $reward;

    public int $difficulty;
}
