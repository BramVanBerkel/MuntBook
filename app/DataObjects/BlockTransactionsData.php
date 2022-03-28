<?php

namespace App\DataObjects;

use App\Enums\TransactionTypeEnum;

class BlockTransactionsData
{
    public function __construct(
        public string $txid,
        public string $amount,
        public TransactionTypeEnum $type,
    ) {
    }
}
