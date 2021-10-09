<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    /**
     * @return int
     * Returns the amount of transactions in the last 24 hrs
     */
    public function countLastTransactions(): int
    {
        return Transaction::query()
            ->where('type', '=', Transaction::TYPE_TRANSACTION)
            ->where('created_at', '>', now()->subDay())
            ->count();
    }
}
