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
        'standard_key_hash_hex',
        'standard_key_hash_address',
        'witness_hex',
        'witness_lock_from_block',
        'witness_lock_until_block',
        'witness_fail_count',
        'witness_action_nonce',
        'witness_pubkey_spend',
        'witness_pubkey_witness',
        'witness_address',
        'transaction_id',
    ];

    public function isWitness()
    {
        return $this->witness_hex !== null;
    }
}
