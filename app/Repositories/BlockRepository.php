<?php

namespace App\Repositories;

use App\DataObjects\BlockData;
use App\Models\Block;
use App\Models\Vout;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlockRepository
{
    public function index()
    {
        $blocks = DB::table('blocks')
            ->select([
                'blocks.height',
                'blocks.created_at',
                DB::raw('sum(vouts.value) as total_value_out'),
                DB::raw('count(distinct transactions) as num_transactions'),
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
                return new BlockData(
                    height: $block->height,
                    date: Carbon::make($block->created_at),
                    totalValueOut: $block->total_value_out,
                    numTransactions: $block->num_transactions,
                );
            });

        return $blocks;
    }

    public function getCurrentHeight(): int
    {
        return Block::max('height') ?? 0;
    }
}
