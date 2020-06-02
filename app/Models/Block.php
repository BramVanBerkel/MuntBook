<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 *
 * @package App\Models
 * @mixin Model
 */
class Block extends Model
{
    protected $table = 'blocks';

    protected $fillable = [
        'hash',
        'confirmations',
        'strippedsize',
        'validated',
        'size',
        'weight',
        'height',
        'version',
        'versionHex',
        'merkleroot',
        'witness_version',
        'witness_versionHex',
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
        'chainwork',
        'previousblockhash',
    ];
}
