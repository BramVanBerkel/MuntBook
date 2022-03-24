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
 * @property int $id
 * @property int $block_height
 * @property string $txid
 * @property int $size
 * @property int $vsize
 * @property int $version
 * @property int $locktime
 * @property string $blockhash
 * @property int $confirmations
 * @property string $blocktime
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Block $block
 * @property-read string $icon
 * @property-read string $icon_name
 * @property-read \App\Models\Address\WitnessAddress|\App\Models\Address\Address|null $rewarded_witness_address
 * @property-read float $total_value_out
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vin[] $vins
 * @property-read int|null $vins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vout[] $vouts
 * @property-read int|null $vouts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBlockHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBlockhash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBlocktime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereConfirmations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereLocktime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTxid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereVsize($value)
 * @mixin \Eloquent
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
        'block_height',
        'blockhash',
        'confirmations',
        'blocktime',
        'type',
        'created_at',
    ];

    protected $appends = [
        'total_value_out'
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
