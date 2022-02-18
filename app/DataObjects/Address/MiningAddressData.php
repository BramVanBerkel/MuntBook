<?php

namespace App\DataObjects\Address;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class MiningAddressData extends DataTransferObject
{
    public string $address;

    public int $firstBlock;

    public Carbon $firstBlockDate;

    public int $lastBlock;

    public Carbon $lastBlockDate;

    public int $totalRewards;

    public float $totalRewardsValue;
}
