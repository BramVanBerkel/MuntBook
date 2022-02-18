<?php

namespace App\Models;

use App\Models\Address\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    const TYPE_DEVELOPMENT_REWARD = 'development_reward';

    const TYPES = [
        self::TYPE_TRANSACTION,
        self::TYPE_MINING,
        self::TYPE_WITNESS,
        self::TYPE_WITNESS_REWARD,
        self::TYPE_WITNESS_FUNDING,
        self::TYPE_DEVELOPMENT_REWARD,
    ];


    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function witnessAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'witness_address_id');
    }

    public function isWitness(): bool
    {
        return $this->type === self::TYPE_WITNESS;
    }
}
