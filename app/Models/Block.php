<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 *
 * @package App\Models
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
        'created_at'
    ];

    protected $appends = [
        'total_value_out'
    ];

    const EMPTY_WITNESS_MERLKEROOT = '0000000000000000000000000000000000000000000000000000000000000000';

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTotalValueOutAttribute()
    {
        $value = 0;

        $this->transactions()->each(function(Transaction $transaction) use (&$value){
            $transaction->vouts()->each(function(Vout $vout) use (&$value){
                if(!$vout->isWitness()){
                    $value += $vout->value;
                }
            });
        });

        return $value;
    }

    public function isWitness(): bool
    {
        return $this->witness_versionHex === '20000000';
    }
}
