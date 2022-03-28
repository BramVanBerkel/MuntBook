<?php

namespace App\Repositories\Address;

use App\DataObjects\Address\WitnessAddressData;
use App\DataObjects\Address\WitnessAddressPartData;
use App\DataObjects\Address\WitnessAddressTransactionsData;
use App\Enums\WitnessAddressPartStatusEnum;
use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;
use App\Models\Vout;
use App\Models\WitnessAddressPart;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WitnessAddressRepository implements AddressRepositoryInterface
{
    /**
     * @param  string  $address
     * @return Collection<WitnessAddressPartData>
     */
    public function getWitnessAddressParts(string $address): Collection
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
                DB::raw('(select max(height) from blocks) - witness_address_parts.last_active_block as blocks_since_last_active'),
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

    public function getAddress(string $address): WitnessAddressData
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
                DB::raw('sum(reward_vouts.value) as total_rewards_value'),
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
            parts: $parts,
            totalRewards: $address->total_rewards,
            totalRewardsValue: (float) $address->total_rewards_value,
        );
    }

    public function getTransactions(string $address): Paginator
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
            ->join('vouts as rewards', function (JoinClause $join) {
                $join->on('rewards.transaction_id', '=', 'transactions.id')
                    ->where('rewards.type', '=', Vout::TYPE_WITNESS_REWARD);
            })
            ->leftJoin('vouts as compounds', function (JoinClause $join) {
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

    public function syncParts(Address $address, Collection $parts)
    {
        $address->witnessAddressParts()->delete();

        foreach ($parts as $part) {
            WitnessAddressPart::create([
                'address_id' => $address->id,
                'type' => $part->get('type'),
                'age' => $part->get('age'),
                'amount' => $part->get('amount'),
                'raw_weight' => $part->get('raw_weight'),
                'adjusted_weight' => $part->get('adjusted_weight'),
                'adjusted_weight_final' => $part->get('adjusted_weight_final'),
                'expected_witness_period' => $part->get('expected_witness_period'),
                'estimated_witness_period' => $part->get('estimated_witness_period'),
                'last_active_block' => $part->get('last_active_block'),
                'lock_from_block' => $part->get('lock_from_block'),
                'lock_until_block' => $part->get('lock_until_block'),
                'lock_period' => $part->get('lock_period'),
                'lock_period_expired' => $part->get('lock_period_expired'),
                'eligible_to_witness' => $part->get('eligible_to_witness'),
                'expired_from_inactivity' => $part->get('expired_from_inactivity'),
                'fail_count' => $part->get('fail_count'),
                'action_nonce' => $part->get('action_nonce'),
            ]);
        }
    }
}
