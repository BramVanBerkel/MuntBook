<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'price',
        'source',
    ];

    public const SOURCE_BITTREX = 'BITTREX';

    public const SOURCES = [
        self::SOURCE_BITTREX,
    ];
}
