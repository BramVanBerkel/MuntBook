<?php

namespace App\Models\Address;

use App\Models\Block;
use App\Models\Vout;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Address\MiningAddress.
 *
 * @property int $id
 * @property string $address
 * @property \App\Enums\AddressTypeEnum $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Block $first_block
 * @property-read Block $last_block
 * @property-read mixed $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Vout[] $minedVouts
 * @property-read int|null $mined_vouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vin[] $vins
 * @property-read int|null $vins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Vout[] $vouts
 * @property-read int|null $vouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WitnessAddressPart[] $witnessAddressParts
 * @property-read int|null $witness_address_parts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiningAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MiningAddress extends Address
{
    public function minedVouts(): HasMany
    {
        return $this->hasMany(Vout::class, 'address_id')
            ->where('type', '=', Vout::TYPE_MINING);
    }

    public function getFirstBlockAttribute(): Block
    {
        return $this->minedVouts()
            ->orderBy('transaction_id')
            ->first()
            ->transaction
            ->block;
    }

    public function getLastBlockAttribute(): Block
    {
        return $this->minedVouts()
            ->orderByDesc('transaction_id')
            ->first()
            ->transaction
            ->block;
    }

    public function getTransactionsAttribute()
    {
        return $this->minedVouts()
            ->orderByDesc('created_at');
    }
}
