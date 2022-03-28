<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Block.
 *
 * @property int $height
 * @property string $hash
 * @property int $confirmations
 * @property int $strippedsize
 * @property int $validated
 * @property int $size
 * @property int $weight
 * @property int $version
 * @property string $merkleroot
 * @property int|null $witness_version
 * @property string|null $witness_time
 * @property string $pow_time
 * @property string|null $witness_merkleroot
 * @property string $time
 * @property int $nonce
 * @property int $pre_nonce
 * @property int $post_nonce
 * @property string $bits
 * @property float $difficulty
 * @property float|null $hashrate
 * @property string $chainwork
 * @property string|null $previousblockhash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Block|null $next
 * @property-read \App\Models\Block|null $previous
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Block newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Block newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Block query()
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereBits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereChainwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereConfirmations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereDifficulty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereHashrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereMerkleroot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block wherePostNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block wherePowTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block wherePreNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block wherePreviousblockhash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereStrippedsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWitnessMerkleroot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWitnessTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWitnessVersion($value)
 * @mixin \Eloquent
 */
class Block extends Model
{
    protected $table = 'blocks';

    protected $primaryKey = 'height';

    protected $fillable = [
        'hash',
        'confirmations',
        'strippedsize',
        'validated',
        'size',
        'weight',
        'height',
        'version',
        'merkleroot',
        'witness_version',
        'witness_time',
        'pow_time',
        'witness_merkleroot',
        'time',
        'mediantime',
        'nonce',
        'pre_nonce',
        'post_nonce',
        'bits',
        'difficulty',
        'hashrate',
        'chainwork',
        'previousblockhash',
        'created_at',
    ];

    protected $appends = [
        'total_value_out',
    ];

    public const EMPTY_WITNESS_MERLKEROOT = '0000000000000000000000000000000000000000000000000000000000000000';

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isWitness(): bool
    {
        return $this->witness_version !== null;
    }

    public function getPreviousAttribute(): ?Block
    {
        return $this->where('height', '<', $this->height)->orderBy('height')->first();
    }

    public function getNextAttribute(): ?Block
    {
        return $this->where('height', '>', $this->height)->orderBy('height')->first();
    }
}
