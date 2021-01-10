<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @package App\Models
 */
class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'txid',
        'size',
        'vsize',
        'version',
        'locktime',
        "block_height",
        'blockhash',
        'confirmations',
        'blocktime',
        'type',
        'created_at',
    ];

    protected $appends = [
        'total_value_out'
    ];

    const TYPE_TRANSACTION = 'transaction';
    const TYPE_WITNESS_FUNDING = 'witness_funding';
    const TYPE_WITNESS = 'witness';

    const TYPES = [
        self::TYPE_TRANSACTION,
        self::TYPE_WITNESS_FUNDING,
        self::TYPE_WITNESS,
    ];

    const EMPTY_TXID = '0000000000000000000000000000000000000000000000000000000000000000';

    const WITNESS_REWARD = 30;

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function vouts()
    {
        return $this->hasMany(Vout::class);
    }

    public function vins()
    {
        return $this->hasMany(Vin::class);
    }

    public function getTotalValueOutAttribute()
    {
        return $this->vouts()
            ->where('type', '<>', Vout::TYPE_WITNESS)
            ->where('scriptpubkey_type', '<>', 'nonstandard')
            ->sum('value');
    }
}
