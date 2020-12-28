<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vin
 *
 * @package App\Models
 */
class Vin extends Model
{
    protected $table = 'vins';

    protected $fillable = [
        'prevout_type',
        'coinbase',
        'tx_height',
        'tx_index',
        'scriptSig_asm',
        'scriptSig_hex',
        'vout',
        'rbf',
        'transaction_id',
        'vout_id',
    ];

    const PREVOUT_TYPE_INDEX = 'index';
    const PREVOUT_TYPE_HASH = 'hash';

    const PREVOUT_TYPES = [
        self::PREVOUT_TYPE_INDEX,
        self::PREVOUT_TYPE_HASH,
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function vout()
    {
        return $this->belongsTo(Vout::class);
    }
}
