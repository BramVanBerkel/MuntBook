<?php

namespace App\Repositories;

use App\DataObjects\BlockData;
use App\DataObjects\BlocksOverviewData;
use App\DataObjects\BlockTransactionsData;
use App\Enums\TransactionTypeEnum;
use App\Models\Block;
use App\Models\Vout;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class BlockRepository
{
    public function index(): CursorPaginator
    {
        $blocks = DB::table('blocks')
            ->select([
                'blocks.height',
                'blocks.created_at as timestamp',
                DB::raw('sum(vouts.value) as value'),
                DB::raw('count(distinct transactions) as transactions'),
            ])
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS);
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->cursorPaginate();

        $blocks->getCollection()
            ->transform(function(object $block) {
                return new BlocksOverviewData(
                    height: (int)$block->height,
                    timestamp: Carbon::make($block->timestamp),
                    transactions: (int)$block->transactions,
                    value: (float)$block->value
                );
            });

        return $blocks;
    }

    /**
     * @throws UnknownProperties
     */
    public function getBlock(int $height): BlockData
    {
        $block = DB::table('blocks')
            ->select([
                'blocks.height',
                'blocks.hash',
                'blocks.created_at as timestamp',
                DB::raw('sum(vouts.value) as value'),
                DB::raw('count(distinct transactions) as transactions'),
                'blocks.version',
                'blocks.merkleroot'
            ])
            ->where('blocks.height', '=', $height)
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
                    ->where('scriptpubkey_type', '<>', 'nonstandard');
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->first();

        return new BlockData(
            height: (int)$block->height,
            hash: $block->hash,
            timestamp: Carbon::make($block->timestamp),
            value: (float)$block->value,
            transactions: (int)$block->transactions,
            version: (int)$block->version,
            merkleRoot: $block->merkleroot,
        );
    }

    public function getTransactions(int $blockHeight)
    {
        return DB::table('transactions')
            ->select([
                'transactions.txid',
                DB::raw('sum(vouts.value) as amount'),
                DB::raw('upper(transactions.type) as type'),
            ])
            ->where('block_height', '=', $blockHeight)
            ->leftJoin('vouts', function(JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
                    ->where('vouts.scriptpubkey_type', 'is distinct from', 'nonstandard');
            })
            ->groupBy('transactions.txid', 'transactions.type')
            ->paginate()
            ->through(function(object $transaction) {
                return new BlockTransactionsData(
                    txid: $transaction->txid,
                    amount: $transaction->amount,
                    type: TransactionTypeEnum::from($transaction->type),
                );
            });
    }

    public function currentHeight(): int
    {
        return DB::table('blocks')
            ->max('height') ?? 0;
    }
}
