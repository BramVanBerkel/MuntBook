<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vout
 *
 * @package App\Models
 */
class Vout extends Model
{
    protected $table = 'vouts';

    protected $fillable = [
        'value',
        'n',
        'standard-key-hash-hex',
        'standard-key-hash-address',
        'witness-hex',
        'witness-lock-from-block',
        'witness-lock-until-block',
        'witness-fail-count',
        'witness-action-nonce',
        'witness-pubkey-spend',
        'witness-pubkey-witness',
        'witness-address',
        'transaction_id',
    ];
}
