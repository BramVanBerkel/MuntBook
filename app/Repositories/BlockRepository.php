<?php

namespace App\Repositories;

use App\DataObjects\AverageBlocktimeData;
use App\DataObjects\BlockData;
use App\DataObjects\BlocksOverviewData;
use App\DataObjects\BlocksPerDayData;
use App\DataObjects\BlockTransactionsData;
use App\DataObjects\NonceData;
use App\Enums\TransactionTypeEnum;
use App\Models\Vout;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlockRepository
{
    public function index(): CursorPaginator
    {
        // todo: remove phpstan-ignore-next-line when https://github.com/nunomaduro/larastan/issues/1197 is fixed
        /** @phpstan-ignore-next-line */
        return DB::table('blocks')
            ->select([
                'blocks.height',
                'blocks.created_at as timestamp',
                DB::raw('sum(vouts.value) as value'),
                DB::raw('count(distinct transactions) as transactions'),
            ])
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->whereNull('witness_hex');
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->cursorPaginate()
            ->through(fn (object $block): BlocksOverviewData => new BlocksOverviewData(
                height: (int) $block->height,
                timestamp: Carbon::make($block->timestamp),
                transactions: (int) $block->transactions,
                value: (float) $block->value
            ));
    }

    /**
     * @throws ModelNotFoundException
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
                'blocks.merkleroot',
            ])
            ->where('blocks.height', '=', $height)
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS)
                    ->where('scriptpubkey_type', '<>', Vout::NONSTANDARD_SCRIPTPUBKEY_TYPE);
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->first();

        if ($block === null) {
            throw new ModelNotFoundException();
        }

        return new BlockData(
            height: (int) $block->height,
            hash: $block->hash,
            timestamp: Carbon::make($block->timestamp),
            value: (float) $block->value,
            transactions: (int) $block->transactions,
            version: (int) $block->version,
            merkleRoot: $block->merkleroot,
        );
    }

    public function getTransactions(int $blockHeight)
    {
        return DB::table('transactions')
            ->select([
                'transactions.txid',
                DB::raw("CASE WHEN transactions.type = 'witness' THEN
                        sum(vouts.value) FILTER (WHERE vouts.witness_hex IS NULL)
                    ELSE
                        sum(vouts.value)
                    END AS amount"),
                DB::raw('upper(transactions.type) as type'),
            ])
            ->where('block_height', '=', $blockHeight)
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS);
            })
            ->groupBy('transactions.txid', 'transactions.type')
            ->paginate()
            ->through(function (object $transaction) {
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

    public function getLatestNonces()
    {
        return DB::table('blocks')
            ->select([
                'height',
                'pre_nonce',
                'post_nonce',
            ])->orderByDesc('height')
            ->limit(2000)
            ->get()
            ->map(function (object $nonce) {
                return new NonceData(
                    height: $nonce->height,
                    preNonce: $nonce->pre_nonce,
                    postNonce: $nonce->post_nonce,
                );
            });
    }

    /**
     * @return Collection<AverageBlocktimeData>
     */
    public function getAverageBlocktimes(): Collection
    {
        return DB::query()
            ->select([
                'date',
                DB::raw('LEAST(EXTRACT(EPOCH FROM avg(delta)), 200) as seconds'),
            ])->fromSub(DB::table('blocks')
                ->select([
                    DB::raw('DATE_TRUNC(\'day\', created_at) AS "date"'),
                    DB::raw('created_at - LAG(created_at, 1) OVER (ORDER BY created_at) AS "delta"'),
                ])
                ->where('created_at', '>', now()->subYear())
                ->groupBy([
                    'date',
                    'created_at',
                ]), 'groups')
            ->groupBy('groups.date')
            ->orderBy('groups.date')
            ->get()
            ->map(fn (object $averageBlocktime) => new AverageBlocktimeData(
                date: new Carbon($averageBlocktime->date),
                seconds: $averageBlocktime->seconds,
            ));
    }

    public function getBlocksPerDay(): Collection
    {
        return DB::query()
            ->select([
                DB::raw("date_trunc('day', created_at) as date"),
                DB::raw('count(blocks.*) as blocks'),
            ])
            ->from('blocks')
            ->whereBetween('created_at', [now()->subYear()->startOfDay(), now()->startOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn (object $blocksPerDay) => new BlocksPerDayData(
                date: new Carbon($blocksPerDay->date),
                blocks: $blocksPerDay->blocks,
            ));
    }

    /**
     * Returns the average difficulty over the last 24hrs.
     *
     * @return float
     */
    public function getAverageDifficulty(): float
    {
        return DB::table('blocks')
            ->selectRaw('AVG(difficulty)')
            ->where('created_at', '>', now()->subDay())
            ->first()
            ->avg;
    }
}
