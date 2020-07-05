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

    public $incrementing = false;

    protected $fillable = [
        'id',
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

    public function getTotalValueOutAttribute()
    {
        return $this->vouts()->sum('value');
    }
}
