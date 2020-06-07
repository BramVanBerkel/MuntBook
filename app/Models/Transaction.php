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
        "txid",
        "hash",
        "size",
        "vsize",
        "version",
        "locktime",
        "block_height"
    ];

    public function block()
    {
        return $this->hasOne(Block::class);
    }

    public function vouts()
    {
        return $this->hasMany(Vout::class);
    }
}
