<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'price',
        'source',
    ];

    public final const SOURCE_BITTREX = 'BITTREX';

    public final const SOURCES = [
        self::SOURCE_BITTREX,
    ];
}
