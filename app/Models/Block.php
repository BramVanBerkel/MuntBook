<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public final const EMPTY_WITNESS_MERLKEROOT = '0000000000000000000000000000000000000000000000000000000000000000';

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
