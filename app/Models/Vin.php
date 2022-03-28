<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Vin.
 *
 * @property int $id
 * @property int $transaction_id
 * @property int|null $vout_id
 * @property string|null $prevout_type
 * @property string|null $coinbase
 * @property int|null $tx_height
 * @property int|null $tx_index
 * @property string|null $scriptSig_asm
 * @property string|null $scriptSig_hex
 * @property int|null $rbf
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Transaction $transaction
 * @property-read \App\Models\Vout|null $vout
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Vin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereCoinbase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin wherePrevoutType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereRbf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereScriptSigAsm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereScriptSigHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereTxHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereTxIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vin whereVoutId($value)
 * @mixin \Eloquent
 */
class Vin extends Model
{
    protected $table = 'vins';

    protected $fillable = [
        'prevout_type',
        'coinbase',
        'tx_height',
        'tx_index',
        'scriptSig_asm',
        'scriptSig_hex',
        'vout',
        'rbf',
        'transaction_id',
        'vout_id',
    ];

    public const PREVOUT_TYPE_INDEX = 'index';
    public const PREVOUT_TYPE_HASH = 'hash';

    public const PREVOUT_TYPES = [
        self::PREVOUT_TYPE_INDEX,
        self::PREVOUT_TYPE_HASH,
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function vout(): BelongsTo
    {
        return $this->belongsTo(Vout::class);
    }
}
