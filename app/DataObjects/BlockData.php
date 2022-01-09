<?php

namespace App\DataObjects;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

class BlockData extends DataTransferObject
{
    public int $height;

    public string $hash;

    public string $version;

    public string $merkleRoot;

    public Carbon $date;

    public float $totalValueOut;

    public int $numTransactions;
}