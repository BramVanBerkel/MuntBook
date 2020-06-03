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
        "blockhash",
        "confirmations",
        "time",
        "blocktime"
    ];
}
