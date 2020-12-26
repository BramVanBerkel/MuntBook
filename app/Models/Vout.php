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
        'transaction_id',
        'address_id',
        'value',
        'n',
        'type',
        'standard_key_hash_hex',
        'standard_key_hash_address',
        'scriptpubkey_type',
        'witness_hex',
        'witness_lock_from_block',
        'witness_lock_until_block',
        'witness_fail_count',
        'witness_action_nonce',
        'witness_pubkey_spend',
        'witness_pubkey_witness',
    ];

    const TYPE_TRANSACTION = 'transaction';
    const TYPE_MINING = 'mining';
    const TYPE_WITNESS = 'witness';
    const TYPE_WITNESS_REWARD = 'witness_reward';
    const TYPE_WITNESS_FUNDING = 'witness_funding';
    const TYPE_WITNESS_COMPOUND = 'witness_compound';


    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function witnessAddress()
    {
        $this->hasOne(Address::class, 'id', 'witness_address_id');
    }

    public function isWitness()
    {
        return $this->witness_hex !== null;
    }
}
