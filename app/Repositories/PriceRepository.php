<?php


namespace App\Repositories;


use App\Models\Price;
use Illuminate\Support\Collection;

class PriceRepository
{
    public function syncPrices(Collection $prices, string $source)
    {
        foreach ($prices as $price) {
            Price::updateOrCreate([
                'timestamp' => $price->get('timestamp'),
                'source' => $source,
            ], [
                'open' => $price->get('open'),
                'high' => $price->get('high'),
                'low' => $price->get('low'),
                'close' => $price->get('close'),
                'volume' => $price->get('volume'),
            ]);
        }
    }
}
