<?php

namespace App\Models;

use App\Enums\AddressTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


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
