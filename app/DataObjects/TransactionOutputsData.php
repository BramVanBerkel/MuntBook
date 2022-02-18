<?php

namespace App\DataObjects;

use Spatie\DataTransferObject\DataTransferObject;

class TransactionOutputsData extends DataTransferObject
{
    public string $address;

    public float $amount;
}
