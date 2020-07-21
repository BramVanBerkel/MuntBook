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
        'created_at',
    ];

    protected $appends = [
        'total_value_out'
    ];

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
        return $this->vouts()->sum('value');
    }
}
