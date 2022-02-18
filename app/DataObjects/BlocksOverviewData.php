<?php

namespace App\DataObjects;

use App\Enums\TransactionTypeEnum;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class BlocksOverviewData extends DataTransferObject
{
    public int $height;

    public Carbon $timestamp;

    public int $transactions;

    public float $value;
}
