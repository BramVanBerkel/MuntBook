<?php

namespace App\Models\Address;

use App\Enums\AddressTypeEnum;
use App\Models\Vin;
use App\Models\Vout;
use App\Models\WitnessAddressPart;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class Address
 * @package App\Models
 */
class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address',
        'type'
    ];

    protected $casts = [
        'type' => AddressTypeEnum::class
    ];

    /**
     * This is the address for Gulden Development.
     * From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden.
     */
    const DEVELOPMENT_ADDRESS = 'GPk2TdvW1bjPAaPL72PXhVfYvyqEHKGrDA';

    public function vouts(): HasMany
    {
        return $this->hasMany(Vout::class, 'address_id');
    }

    public function vins(): HasManyThrough
    {
        return $this->hasManyThrough(Vin::class, Vout::class, 'id');
    }

    public function witnessAddressParts(): HasMany
    {
        return $this->hasMany(WitnessAddressPart::class, 'address_id');
    }

    public function transactionVins(): Builder
    {
        return DB::table('vins')->select([
            'transactions.txid', 'blocks.created_at', DB::raw('sum(vouts.value) as value'), DB::raw("'vin' as type")
        ])->join('vouts', 'vins.vout_id', '=', 'vouts.id')
            ->join('transactions', 'vins.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $this->id)
            ->groupBy(['vins.transaction_id', 'transactions.txid', 'blocks.created_at']);
    }

    public function transactionVouts(): Builder
    {
        return DB::table('vouts')->select([
            'transactions.txid', 'blocks.created_at', DB::raw('sum(vouts.value) as value'), DB::raw("'vout' as type")
        ])->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', '=', 'blocks.height')
            ->where('vouts.address_id', '=', $this->id)
            ->groupBy('vouts.transaction_id', 'transactions.txid', 'blocks.created_at');
    }

    public function transactions(): Paginator
    {
        $transactions = ($this->address !== Address::DEVELOPMENT_ADDRESS) ?
            $this->transactionVouts()->union($this->transactionVins()) :
            $this->transactionVins();

        return $transactions->orderByDesc('created_at')->paginate();
    }

    public function getTotalValueInAttribute(): float
    {
        return (float)DB::query()->fromSub($this->transactionVins(), 'vins')->sum('value');
    }

    public function getTotalValueOutAttribute(): float
    {
        return (float)DB::query()->fromSub($this->transactionVouts(), 'vouts')->sum('value');
    }

    public function getTotalValueAttribute(): float
    {
        return $this->total_value_out - $this->total_value_in;
    }
}