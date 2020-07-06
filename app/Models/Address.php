<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * @package App\Models
 * @mixin \Eloquent
 */
class Address extends Model
{
    protected $table = 'addresses';

    public $timestamps = true;

    protected $fillable = [
        'address',
        'type'
    ];

    const TYPE_ADDRESS = 'address';
    const TYPE_WITNESS_ADDRESS = 'witness_address';
    const TYPE_MINING_ADDRESS = 'mining_address';

    public function vouts()
    {
        return $this->belongsToMany(Vout::class);
    }
}
