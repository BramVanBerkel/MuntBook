<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public final const NONSTANDARD_SCRIPTPUBKEY_TYPE = 'nonstandard';

    public final const TYPE_TRANSACTION = 'transaction';

    public final const TYPE_MINING = 'mining';

    public final const TYPE_WITNESS = 'witness';

    public final const TYPE_WITNESS_REWARD = 'witness_reward';

    public final const TYPE_WITNESS_FUNDING = 'witness_funding';

    public final const TYPE_DEVELOPMENT_REWARD = 'development_reward';

    public final const TYPES = [
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
