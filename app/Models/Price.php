<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'source',
    ];

    const SOURCE_BITTREX = 'BITTREX';

    const SOURCES = [
        self::SOURCE_BITTREX,
    ];
}