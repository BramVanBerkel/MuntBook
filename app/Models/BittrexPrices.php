<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BittrexPrices extends Model
{
    protected $table = 'prices.bittrex';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'quote_volume',
    ];
}
