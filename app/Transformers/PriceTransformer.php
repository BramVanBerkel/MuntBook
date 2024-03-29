<?php

namespace App\Transformers;

use App\Models\Price;
use Illuminate\Support\Collection;

class PriceTransformer
{
    public function bittrexTransformer(Collection $prices)
    {
        return $prices->map(function ($price) {
            $average = (int) array_sum([
                (float) $price->open * 100_000_000,
                (float) $price->high * 100_000_000,
                (float) $price->low * 100_000_000,
                (float) $price->close * 100_000_000,
            ]) / 4;

            return [
                'timestamp' => $price->startsAt,
                'price' => $average,
                'source' => Price::SOURCE_BITTREX,
            ];
        });
    }
}
