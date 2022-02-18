<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class AddressData extends DataTransferObject
{
    public string $address;

    public Carbon $firstSeen;

    public int $totalTransactions;

    public float $totalReceived;

    public float $totalSpent;

    public float $unspent;
}
