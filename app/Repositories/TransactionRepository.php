<?php

namespace App\Repositories;

use App\DataObjects\TransactionData;
use App\Models\Transaction;
use App\Models\Vout;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public function getTransactions(int $blockHeight)
    {
        return DB::table('transactions')
            ->select([
                'transactions.txid',
                'transactions.type',
                DB::raw('sum(vouts.value) as amount'),
            ])
            ->where('block_height', '=', $blockHeight)
            ->leftJoin('vouts', function(JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
                    ->where('vouts.scriptpubkey_type', 'is distinct from', 'nonstandard');
            })
            ->groupBy('transactions.txid', 'transactions.type')
            ->get()
            ->map(function(object $transaction) {
                return new TransactionData(
                    txid: $transaction->txid,
                    amount: $transaction->amount,
                    type: $transaction->type,
                );
            });
    }

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
