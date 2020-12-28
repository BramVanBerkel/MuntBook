<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * From block number 1030000 this address will receive 40 Gulden for each block.
     */
    const DEVELOPMENT_ADDRESS = 'GPk2TdvW1bjPAaPL72PXhVfYvyqEHKGrDA';

    const TYPE_ADDRESS = 'address';
    const TYPE_WITNESS_ADDRESS = 'witness_address';
    const TYPE_MINING_ADDRESS = 'mining_address';

    const TYPES = [
        self::TYPE_ADDRESS,
        self::TYPE_WITNESS_ADDRESS,
        self::TYPE_MINING_ADDRESS,
    ];

    public function vouts()
    {
        return $this->hasMany(Vout::class);
    }

    public function vins()
    {
        return $this->hasManyThrough(Vin::class, Vout::class);
    }
}
