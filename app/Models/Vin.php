<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vin
 *
 * @package App\Models
 */
class Vin extends Model
{
    protected $table = 'vins';

    protected $fillable = [
        'transaction_id',
        'vout_id',
    ];

    const PREVOUT_TYPE_INDEX = 'index';
    const PREVOUT_TYPE_HASH = 'hash';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function vout()
    {
        return $this->belongsTo(Vout::class);
    }
}
