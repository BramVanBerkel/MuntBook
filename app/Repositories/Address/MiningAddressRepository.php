<?php

namespace App\Repositories\Address;

use App\DataObjects\Address\MiningAddressData;
use App\DataObjects\Address\MiningAddressTransactionData;
use App\Interfaces\AddressRepositoryInterface;
use App\Models\Vout;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class MiningAddressRepository implements AddressRepositoryInterface
{
    public function getAddress(string $address): MiningAddressData
    {
        $address = DB::table('addresses')
            ->select([
                'addresses.address',
                DB::raw('sum(vouts.value) as total_rewards_value'),
                DB::raw('count(vouts.*) as total_rewards'),
                DB::raw('min(blocks.height) as first_block'),
                DB::raw('min(blocks.created_at) as first_block_date'),
                DB::raw('max(blocks.height) as last_block'),
                DB::raw('max(blocks.created_at) as last_block_date'),
            ])
            ->join('vouts', 'vouts.address_id', '=', 'addresses.id')
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->groupBy([
                'addresses.address',
            ])
            ->first();

        return new MiningAddressData(
            address: $address->address,
            firstBlock: $address->first_block,
            firstBlockDate: Carbon::parse($address->first_block_date),
            lastBlock: $address->last_block,
            lastBlockDate: Carbon::parse($address->last_block_date),
            totalRewards: $address->total_rewards,
            totalRewardsValue: $address->total_rewards_value,
        );
    }

    public function getTransactions(string $address): Paginator
    {
        return DB::table('addresses')
            ->select([
                'blocks.height',
                'blocks.created_at as date',
                'vouts.value as reward',
                'blocks.difficulty',
            ])
            ->leftJoin('vouts', function (JoinClause $join) {
                $join->on('vouts.address_id', '=', 'addresses.id')
                    ->where('vouts.type', '=', Vout::TYPE_MINING);
            })
            ->leftJoin('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->leftJoin('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->orderByDesc('transactions.created_at')
            ->paginate()
            ->through(fn (object $transaction) => new MiningAddressTransactionData(
                height: $transaction->height,
                date: Carbon::parse($transaction->date),
                reward: $transaction->reward,
                difficulty: $transaction->difficulty,
            ));
    }
}
