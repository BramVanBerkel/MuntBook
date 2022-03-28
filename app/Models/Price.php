<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Price.
 *
 * @property int $id
 * @property string $timestamp
 * @property float $price
 * @property string $source
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereTimestamp($value)
 * @mixin \Eloquent
 */
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
