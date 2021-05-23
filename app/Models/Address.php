<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    /**
     * This is the address for Gulden Development.
     * From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden.
     */
    const DEVELOPMENT_ADDRESS = 'GPk2TdvW1bjPAaPL72PXhVfYvyqEHKGrDA';

    const TYPE_ADDRESS = 'address';
    const TYPE_WITNESS = 'witness';
    const TYPE_MINING = 'mining';

    const TYPES = [
        self::TYPE_ADDRESS,
        self::TYPE_WITNESS,
        self::TYPE_MINING,
    ];

    public function vouts(): HasMany
    {
        return $this->hasMany(Vout::class);
    }

    public function vins(): HasManyThrough
    {
        return $this->hasManyThrough(Vin::class, Vout::class);
    }

    public function witnessAddressParts(): HasMany
    {
        return $this->hasMany(WitnessAddressPart::class);
    }
}
