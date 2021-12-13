<?php

namespace App\DataObjects;

use Spatie\DataTransferObject\DataTransferObject;

class TransactionData extends DataTransferObject
{
    public string $txid;

    public float $amount;

    public string $type;
}
