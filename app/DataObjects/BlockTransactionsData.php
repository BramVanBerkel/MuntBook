<?php

namespace App\DataObjects;

use App\Enums\TransactionTypeEnum;
use Spatie\DataTransferObject\DataTransferObject;

class BlockTransactionsData extends DataTransferObject
{
    public string $txid;

    public string $amount;

    public TransactionTypeEnum $type;
}
