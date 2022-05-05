<?php

namespace App\Repositories;

use App\DataObjects\TransactionData;
use App\DataObjects\TransactionOutputsData;
use App\Models\Transaction;
use App\Models\Vout;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public function getTransaction(string $txid)
    {
        $transaction = DB::table('transactions')
            ->select([
                'transactions.txid',
                'transactions.block_height as height',
                'transactions.created_at as timestamp',
                DB::raw('sum(vouts.value) as amount'),
                'transactions.version',
                'addresses.address as rewarded_witness_address',
                'transactions.type',
            ])
            ->leftJoin('vouts', fn (JoinClause $join) => $join->on('vouts.transaction_id', '=', 'transactions.id')
                ->where('vouts.type', '<>', Vout::TYPE_WITNESS))
            ->leftJoin('vouts as reward_vout', function (JoinClause $join) {
                $join->on('reward_vout.transaction_id', '=', 'transactions.id')
                    ->where('reward_vout.type', '=', Vout::TYPE_WITNESS);
            })
            ->leftJoin('addresses', 'reward_vout.address_id', '=', 'addresses.id')
            ->where('txid', '=', $txid)
            ->groupBy(
                'transactions.txid',
                'transactions.block_height',
                'transactions.created_at',
                'transactions.version',
                'addresses.address',
                'transactions.type',
            )
            ->first();

        if ($transaction === null) {
            throw new ModelNotFoundException();
        }

        return new TransactionData(
            txid: $transaction->txid,
            height: (int) $transaction->height,
            timestamp: Carbon::make($transaction->timestamp),
            amount: (float) $transaction->amount,
            version: (int) $transaction->version,
            rewardedWitnessAddress: $transaction->rewarded_witness_address,
            type: $transaction->type,
        );
    }

    public function getOutputs(string $txid)
    {
        $inputs = DB::table('transactions')
            ->select([
                'addresses.address',
                DB::raw('-sum(vouts.value) as value'),
                DB::raw("'input' as type"),
            ])
            ->join('vins', fn (JoinClause $join) => $join->on('vins.transaction_id', '=', 'transactions.id')
                ->whereNotNull('vout_id'))
            ->join('vouts', fn (JoinClause $join) => $join->on('vins.vout_id', '=', 'vouts.id')
                ->where('vouts.type', '<>', Vout::TYPE_WITNESS))
            ->join('addresses', 'vouts.address_id', '=', 'addresses.id')
            ->where('transactions.txid', '=', $txid)
            ->groupBy('addresses.address', 'vins.id');

        $outputs = DB::table('transactions')
            ->select([
                'addresses.address',
                DB::raw('sum(vouts.value) as value'),
                DB::raw("'output' as type"),
            ])
            ->join('vouts', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('addresses', 'vouts.address_id', '=', 'addresses.id')
            ->where('transactions.txid', '=', $txid)
            ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
            ->groupBy('addresses.address');

        return $inputs->union($outputs)
            ->orderBy('type')
            ->get()
            ->map(fn ($output) => new TransactionOutputsData(
                address: $output->address,
                amount: (float) $output->value,
            ));
    }

    /**            Returns the amount of transactions in the last 24 hrs.
     */
    public function countLastTransactions(): int
    {
        return Transaction::query()
            ->where('type', '=', Transaction::TYPE_TRANSACTION)
            ->where('created_at', '>', now()->subDay())
            ->count();
    }
}
