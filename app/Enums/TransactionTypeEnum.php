<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case TRANSACTION = 'TRANSACTION';
    case MINING = 'MINING';
    case WITNESS = 'WITNESS';
    case WITNESS_FUNDING = 'WITNESS_FUNDING';
}
