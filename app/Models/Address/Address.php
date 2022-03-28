<?php

namespace App\Models\Address;

use App\Enums\AddressTypeEnum;
use App\Models\Vin;
use App\Models\Vout;
use App\Models\WitnessAddressPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Address.
 *
 * @property int $id
 * @property string $address
 * @property AddressTypeEnum $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Vin[] $vins
 * @property-read int|null $vins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Vout[] $vouts
 * @property-read int|null $vouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|WitnessAddressPart[] $witnessAddressParts
 * @property-read int|null $witness_address_parts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address',
        'type',
    ];

    protected $casts = [
        'type' => AddressTypeEnum::class,
    ];

    /**
     * This is the address for Gulden Development.
     * From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden.
     */
    public const DEVELOPMENT_ADDRESS = 'GPk2TdvW1bjPAaPL72PXhVfYvyqEHKGrDA';

    public function newFromBuilder($attributes = [], $connection = null): MiningAddress|WitnessAddress|Address
    {
        // Although the $attributes = [] above says $attributes should be an array, it accually is an object,
        // so we have to manually convert it to an array
        $attributes = (array) $attributes;

        //todo: refactor to enum
        $instance = match ($attributes['type']) {
            AddressTypeEnum::MINING->name => new MiningAddress(),
            AddressTypeEnum::WITNESS->name => new WitnessAddress(),
            default => new Address(),
        };

        $instance->setRawAttributes((array) $attributes, true);

        $instance->setConnection($connection ?: $this->getConnectionName());

        $instance->fireModelEvent('retrieved', false);

        return $instance;
    }

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

    public function isDevelopmentAddress(): bool
    {
        return $this->address === self::DEVELOPMENT_ADDRESS;
    }
}
