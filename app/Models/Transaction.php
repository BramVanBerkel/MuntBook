<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'txid',
        'size',
        'vsize',
        'version',
        'locktime',
        'block_height',
        'blockhash',
        'confirmations',
        'blocktime',
        'type',
        'created_at',
    ];

    protected $appends = [
        'total_value_out',
    ];

    public const TYPE_TRANSACTION = 'TRANSACTION';
    public const TYPE_WITNESS_FUNDING = 'WITNESS_FUNDING';
    public const TYPE_WITNESS = 'WITNESS';
    public const TYPE_MINING = 'MINING';

    public const TYPES = [ //todo: convert to enum
        self::TYPE_TRANSACTION,
        self::TYPE_WITNESS_FUNDING,
        self::TYPE_WITNESS,
        self::TYPE_MINING,
    ];

    public const ICONS = [
        self::TYPE_TRANSACTION => 'exchange-alt',
        self::TYPE_WITNESS_FUNDING => 'piggy-bank',
        self::TYPE_WITNESS => 'glasses',
        self::TYPE_MINING => 'calculator',
    ];

    public const EMPTY_TXID = '0000000000000000000000000000000000000000000000000000000000000000';

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
        return (float) $this->vouts()
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
}
