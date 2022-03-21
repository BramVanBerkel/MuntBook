<?php

namespace App\Models;

use App\Models\Address\Address;
use App\Models\Address\WitnessAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'type', // todo: refactor type to uppercase
        'created_at',
    ];

    protected $appends = [
        'total_value_out'
    ];

    const TYPE_TRANSACTION = 'transaction';
    const TYPE_WITNESS_FUNDING = 'witness_funding';
    const TYPE_WITNESS = 'witness';
    const TYPE_MINING = 'mining';

    const TYPES = [ //todo: convert to enum
        self::TYPE_TRANSACTION,
        self::TYPE_WITNESS_FUNDING,
        self::TYPE_WITNESS,
        self::TYPE_MINING,
    ];

    const ICONS = [
        self::TYPE_TRANSACTION => 'exchange-alt',
        self::TYPE_WITNESS_FUNDING => 'piggy-bank',
        self::TYPE_WITNESS => 'glasses',
        self::TYPE_MINING => 'calculator',
    ];

    const EMPTY_TXID = '0000000000000000000000000000000000000000000000000000000000000000';

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function vouts(): HasMany
    {
        return $this->hasMany(Vout::class);
    }

    public function vins(): HasMany
    {
        return $this->hasMany(Vin::class);
    }

    public function getTotalValueOutAttribute(): float
    {
        return (float)$this->vouts()
            ->where('type', '<>', Vout::TYPE_WITNESS)
            ->where('scriptpubkey_type', 'is distinct from', Vout::NONSTANDARD_SCRIPTPUBKEY_TYPE)
            ->sum('value');
    }

    public function getIconAttribute(): string
    {
        return Transaction::ICONS[$this->type];
    }

    public function getIconNameAttribute(): string
    {
        return Str::ucfirst(str_replace('_', ' ', $this->type));
    }

    public function getRewardedWitnessAddressAttribute(): WitnessAddress|Address|null
    {
        if($this->type !== Transaction::TYPE_WITNESS) {
            return null;
        }

        return $this->vouts()->first()->address;
    }
}
