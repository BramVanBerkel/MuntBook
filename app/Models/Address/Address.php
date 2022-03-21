<?php

namespace App\Models\Address;

use App\Enums\AddressTypeEnum;
use App\Models\Vin;
use App\Models\Vout;
use App\Models\WitnessAddressPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'type' => AddressTypeEnum::class,
    ];

    /**
     * This is the address for Gulden Development.
     * From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden.
     */
    const DEVELOPMENT_ADDRESS = 'GPk2TdvW1bjPAaPL72PXhVfYvyqEHKGrDA';

    public function newFromBuilder($attributes = [], $connection = null): MiningAddress|WitnessAddress|Address
    {
        //todo: refactor to enum
        $instance = match($attributes->type) {
            AddressTypeEnum::MINING->name => new MiningAddress(),
            AddressTypeEnum::WITNESS->name => new WitnessAddress(),
            default => new Address(),
        };

        $instance->setRawAttributes((array)$attributes, true);

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

    //todo: refactor to new laravel 9 attributes
    public function getIsDevelopmentAddressAttribute(): bool
    {
        return $this->address === self::DEVELOPMENT_ADDRESS;
    }
}
