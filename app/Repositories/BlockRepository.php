<?php

namespace App\Repositories;

use App\DataObjects\BlockData;
use App\Models\Block;
use App\Models\Vout;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlockRepository
{
    public function __construct(
        private TransactionRepository $transactionRepository
    ) { }

    public function getBlock(int $height)
    {
        $block = DB::table('blocks')
            ->select($this->select())
            ->where('blocks.height', '=', $height)
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS);
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->first();

        return $this->transformBlock($block, $this->transactionRepository->getTransactions($height));
    }

    public function index(): CursorPaginator
    {
        $blocks = DB::table('blocks')
            ->select($this->select())
            ->leftJoin('transactions', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.transaction_id', '=', 'transactions.id')
                    ->where('vouts.type', '<>', Vout::TYPE_WITNESS);
            })
            ->orderByDesc('height')
            ->groupBy('blocks.height')
            ->cursorPaginate();

        $blocks->getCollection()
            ->transform(fn($block) => $this->transformBlock($block));

        return $blocks;
    }

    private function transformBlock(object $block, ?Collection $transactions = null): BlockData
    {
        return new BlockData(
            height: $block->height,
            hash: $block->hash,
            version: $block->version,
            merkleRoot: $block->merkleroot,
            date: Carbon::make($block->created_at),
            totalValueOut: $block->total_value_out,
            numTransactions: $block->num_transactions,
            transactions: $transactions,
        );
    }

    private function select(): array
    {
        return [
            'blocks.height',
            'blocks.hash',
            'blocks.version',
            'blocks.merkleroot',
            'blocks.created_at',
            DB::raw('sum(vouts.value) as total_value_out'),
            DB::raw('count(distinct transactions) as num_transactions'),
        ];
    }

    public function getCurrentHeight(): int
    {
        return DB::table('blocks')
            ->max('height') ?? 0;
    }
}
