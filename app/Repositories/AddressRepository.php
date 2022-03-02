<?php

namespace App\Repositories;

use App\DataObjects\Address\AddressData;
use App\DataObjects\Address\AddressTransactionData;
use App\DataObjects\Address\MiningAddressData;
use App\DataObjects\Address\MiningAddressTransactionData;
use App\DataObjects\Address\WitnessAddressData;
use App\DataObjects\Address\WitnessAddressPartData;
use App\DataObjects\Address\WitnessAddressTransactionsData;
use App\Enums\AddressTypeEnum;
use App\Enums\WitnessAddressPartStatusEnum;
use App\Models\Address\Address;
use App\Models\Vout;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;

class AddressRepository
{
    public function getType(string $address): ?AddressTypeEnum
    {
        $type = DB::table('addresses')
            ->where('address', '=', $address)
            ->select(['type'])
            ->first()
            ?->type;

        return AddressTypeEnum::tryFrom($type);
    }

    public function getAddress(string $address): AddressData
    {
        $address = DB::table('addresses')
            ->select([
                'addresses.address',
                DB::raw('count(inputs.*) + count(outputs.*) as total_transactions'),
                DB::raw('min(blocks.created_at) as first_seen'),
                DB::raw('sum(inputs.value) as total_received'),
                DB::raw('coalesce(sum(outputs.value), 0) as total_spent'),
                DB::raw('coalesce(sum(inputs.value) - sum(outputs.value), 0) as unspent')
            ])
            ->where('addresses.address', '=', $address)
            ->leftJoin('vouts as inputs', 'inputs.address_id', '=', 'addresses.id')
            ->leftJoin('transactions', 'inputs.transaction_id', '=', 'transactions.id')
            ->leftJoin('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->leftJoin('vins', 'vins.vout_id', '=', 'inputs.id')
            ->leftJoin('vouts as outputs', 'vins.vout_id', '=', 'outputs.id')
            ->groupBy([
                'addresses.address',
            ])
            ->first();

        return new AddressData(
            address: $address->address,
            firstSeen: Carbon::parse($address->first_seen),
            totalTransactions: $address->total_transactions,
            totalReceived: $address->total_received,
            totalSpent: $address->total_spent,
            unspent: $address->unspent,
        );
    }

    public function getAddressTransactions(string $address): Paginator|CursorPaginator
    {
        $outputs = DB::table('addresses')
            ->select([
                'transactions.txid',
                'blocks.created_at as timestamp',
                DB::raw('sum(-outputs.value) as amount'),
            ])
            ->join('vouts', 'vouts.address_id', '=', 'addresses.id')
            ->join('vins', 'vins.vout_id', '=', 'vouts.id')
            ->join('vouts as outputs', 'vins.vout_id', '=', 'outputs.id')
            ->join('transactions', 'vins.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->groupBy([
                'transactions.txid',
                'blocks.created_at',
            ])
            ->orderByDesc('timestamp');

        $inputs = DB::table('addresses')
            ->select([
                'transactions.txid',
                'blocks.created_at as timestamp',
                DB::raw('vouts.value as amount'),
            ])
            ->join('vouts', 'vouts.address_id', '=', 'addresses.id')
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->orderByDesc('timestamp');

        if ($address === Address::DEVELOPMENT_ADDRESS) {
            $transactions = $outputs->cursorPaginate();
        } else {
            $transactions = $outputs->union($inputs)->paginate();
        }

        $transactions->through(function (object $transaction) {
            return new AddressTransactionData(
                txid: $transaction->txid,
                timestamp: Carbon::parse($transaction->timestamp),
                amount: $transaction->amount,
            );
        });

        return $transactions;
    }

    public function getWitnessAddress(string $address)
    {
        $parts = $this->getWitnessAddressParts($address);

        $address = DB::table('addresses')
            ->select([
                'addresses.address',
                DB::raw('(select sum(amount) from witness_address_parts where address_id = addresses.id) as total_amount_locked'),
                DB::raw('(select sum(adjusted_weight) from witness_address_parts where address_id = addresses.id) as total_weight'),
                'witness_address_parts.lock_from_block',
                'blocks.created_at as lock_from_timestamp',
                'witness_address_parts.lock_until_block',
                DB::raw(sprintf("now() + (
                    (witness_address_parts.lock_until_block - (
                      select
                        max(height)
                      from
                        blocks
                    )
                  ) * %d) * interval '1 second' as lock_until_timestamp", config('gulden.blocktime'))),
                DB::raw('count(reward_vouts.*) as total_rewards'),
                DB::raw('sum(reward_vouts.value) as total_rewards_value')
            ])
            ->distinct('witness_address_parts.address_id')
            ->leftJoin('witness_address_parts', 'witness_address_parts.address_id', '=', 'addresses.id')
            ->leftJoin('blocks', 'witness_address_parts.lock_from_block', '=', 'blocks.height')
            ->join('vouts', function (JoinClause $join) {
                $join->on('vouts.address_id', '=', 'addresses.id')
                    ->where('vouts.type', '=', Vout::TYPE_WITNESS);
            })
            ->join('vouts as reward_vouts', function (JoinClause $join) {
                $join->on('reward_vouts.transaction_id', '=', 'vouts.transaction_id')
                    ->where('reward_vouts.n', '=', 1);
            })
            ->where('addresses.address', '=', $address)
            ->groupBy(
                'witness_address_parts.id',
                'witness_address_parts.lock_from_block',
                'blocks.created_at',
                'witness_address_parts.lock_until_block',
                'witness_address_parts.address_id',
                'addresses.address',
                'addresses.id',
            )->first();

        return new WitnessAddressData(
            address: $address->address,
            totalAmountLocked: $address->total_amount_locked,
            totalWeight: $address->total_weight,
            lockedFromBlock: $address->lock_from_block,
            lockedFromTimestamp: ($address->lock_from_timestamp) ? Carbon::parse($address->lock_from_timestamp) : null,
            lockedUntilBlock: $address->lock_until_block,
            lockedUntilTimestamp: ($address->lock_until_timestamp) ? Carbon::parse($address->lock_until_timestamp) : null,
            totalRewards: $address->total_rewards,
            parts: $parts,
            totalRewardsValue: (float)$address->total_rewards_value,
        );
    }

    public function getWitnessAddressTransactions(string $address): Paginator
    {
        $transactions = DB::table('addresses')
            ->select([
                'blocks.height',
                'blocks.created_at as timestamp',
                DB::raw('sum(rewards.value) as reward'),
                DB::raw('coalesce(compounds.value, 0) as compound'),
            ])
            ->join('vouts', function (JoinClause $join) {
                $join->on('vouts.address_id', '=', 'addresses.id')
                    ->where('vouts.type', '=', Vout::TYPE_WITNESS);
            })
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->join('vouts as rewards', function(JoinClause $join) {
                $join->on('rewards.transaction_id', '=', 'transactions.id')
                    ->where('rewards.type', '=', Vout::TYPE_WITNESS_REWARD);
            })
            ->leftJoin('vouts as compounds', function(JoinClause $join) {
                $join->on('compounds.transaction_id', '=', 'transactions.id')
                    ->where('compounds.address_id', '=', DB::raw('addresses.id'))
                    ->where('compounds.type', '<>', Vout::TYPE_WITNESS);
            })
            ->where('addresses.address', '=', $address)
            ->groupBy([
                'blocks.height',
                'rewards.value',
                'compounds.value',
            ])
            ->orderByDesc('timestamp')
            ->paginate();

        return $transactions->through(function (object $transaction) {
            return new WitnessAddressTransactionsData(
                height: $transaction->height,
                timestamp: Carbon::parse($transaction->timestamp),
                reward: $transaction->reward,
                compound: $transaction->compound,
            );
        });
    }

    public function getWitnessAddressParts(string $address)
    {
        $parts = DB::table('witness_address_parts')
            ->select([
                'witness_address_parts.amount as value',
                'witness_address_parts.adjusted_weight as weight',
                DB::raw('case
                    when witness_address_parts.lock_period_expired = true then \'lock_period_expired\'
                    when witness_address_parts.eligible_to_witness = false then \'not_eligible_to_witness\'
                    when witness_address_parts.expired_from_inactivity = true then \'expired_from_inactivity\'
                    when (select max(height) from blocks) - witness_address_parts.last_active_block < 100 then \'cooldown\'
                    else \'eligible_to_witness\'
	            end as status'),
                DB::raw('(select max(height) from blocks) - witness_address_parts.last_active_block as blocks_since_last_active')
            ])
            ->join('addresses', 'addresses.id', '=', 'witness_address_parts.address_id')
            ->where('addresses.address', '=', $address)
            ->get();

        return $parts->map(function (object $part) {
            $status = WitnessAddressPartStatusEnum::from($part->status);

            return new WitnessAddressPartData(
                value: $part->value,
                weight: $part->weight,
                status: $status,
                blocksSinceLastActive: $part->blocks_since_last_active,
            );
        });
    }

    public function getMiningAddress(string $address)
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

    public function getMiningAddressTransactions(string $address)
    {
        return DB::table('addresses')
            ->select([
                'blocks.height',
                'blocks.created_at as date',
                'vouts.value as reward',
                'blocks.difficulty'
            ])
            ->leftJoin('vouts', function(JoinClause $join) {
                $join->on('vouts.address_id', '=', 'addresses.id')
                    ->where('vouts.type', '=', Vout::TYPE_MINING);
            })
            ->leftJoin('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->leftJoin('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->orderByDesc('transactions.created_at')
            ->paginate()
            ->through(function(object $transaction) {
                return new MiningAddressTransactionData(
                    height: $transaction->height,
                    date: Carbon::parse($transaction->date),
                    reward: $transaction->reward,
                    difficulty: $transaction->difficulty,
                );
            });
    }
}
