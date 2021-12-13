<?php

namespace App\DataObjects;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

class BlockData extends DataTransferObject
{
    public int $height;

    public Carbon $date;

    public float $totalValueOut;

    public int $numTransactions;

    public ?Collection $transactions;
}
