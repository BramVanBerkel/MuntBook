<?php

namespace App\Models;

use App\Models\Address\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Vout.
 *
 * @property int $id
 * @property int $transaction_id
 * @property int|null $address_id
 * @property string $type
 * @property float $value
 * @property int $n
 * @property string|null $standard_key_hash_hex
 * @property string|null $standard_key_hash_address
 * @property string|null $scriptpubkey_type
 * @property string|null $witness_hex
 * @property int|null $witness_lock_from_block
 * @property int|null $witness_lock_until_block
 * @property int|null $witness_fail_count
 * @property int|null $witness_action_nonce
 * @property string|null $witness_pubkey_spend
 * @property string|null $witness_pubkey_witness
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Address|null $address
 * @property-read \App\Models\Transaction $transaction
 * @property-read Address|null $witnessAddress
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Vout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vout query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereScriptpubkeyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereStandardKeyHashAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereStandardKeyHashHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessActionNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessFailCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessLockFromBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessLockUntilBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessPubkeySpend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vout whereWitnessPubkeyWitness($value)
 * @mixin \Eloquent
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

    public const NONSTANDARD_SCRIPTPUBKEY_TYPE = 'nonstandard';

    public const TYPE_TRANSACTION = 'transaction';
    public const TYPE_MINING = 'mining';
    public const TYPE_WITNESS = 'witness';
    public const TYPE_WITNESS_REWARD = 'witness_reward';
    public const TYPE_WITNESS_FUNDING = 'witness_funding';
    public const TYPE_DEVELOPMENT_REWARD = 'development_reward';

    public const TYPES = [
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
