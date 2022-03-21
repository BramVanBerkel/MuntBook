<?php

namespace App\Repositories\Address;

use App\DataObjects\Address\AddressData;
use App\DataObjects\Address\AddressTransactionData;
use App\DataObjects\Address\MiningAddressData;
use App\DataObjects\Address\MiningAddressTransactionData;
use App\DataObjects\Address\WitnessAddressData;
use App\DataObjects\Address\WitnessAddressPartData;
use App\DataObjects\Address\WitnessAddressTransactionsData;
use App\Enums\AddressTypeEnum;
use App\Enums\WitnessAddressPartStatusEnum;
use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address\Address;
use App\Models\Vout;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;

class AddressRepository implements AddressRepositoryInterface
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

    public function getTransactions(string $address): Paginator|CursorPaginator
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
}
