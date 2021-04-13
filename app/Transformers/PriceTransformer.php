<?php


namespace App\Transformers;


use App\Models\Price;
use Illuminate\Support\Collection;

class PriceTransformer
{
    public function bittrexTransformer(Collection $prices)
    {
        return $prices->map(function($price) {
            return [
                'timestamp' => $price->startsAt,
                'open' => $price->open * 100000000,
                'high' => $price->high * 100000000,
                'low' => $price->low * 100000000,
                'close' => $price->close * 100000000,
                'volume' => $price->volume,
                'source' => Price::SOURCE_BITTREX,
            ];
        });
    }
}
