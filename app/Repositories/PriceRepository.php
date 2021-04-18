<?php


namespace App\Repositories;


use App\Enums\PriceTimeframeEnum;
use App\Models\Price;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    public function getPrices(int $timeframe, string $source)
    {
        return Cache::remember("prices_{$timeframe}_{$source}", now()->addSeconds($timeframe), function() use($timeframe, $source) {
            return DB::table('prices')
                ->selectRaw('(MAX(ARRAY[id, open]))[2] AS open')
                ->selectRaw('MAX(high) AS high')
                ->selectRaw('MIN(low) AS low')
                ->selectRaw('(MIN(ARRAY[id, close]))[2] AS close')
                ->selectRaw('SUM(volume) as volume')
                ->selectRaw('TO_TIMESTAMP(FLOOR((EXTRACT(\'epoch\' FROM timestamp) / ? )) * ?) AS time', [$timeframe, $timeframe])
                ->where('source', '=', $source)
                ->where('timestamp', '>', now()->subSeconds($timeframe * 14400))
                ->groupBy('time')
                ->orderBy('time')
                ->get()
                ->map(function ($price) {
                    return [
                        "open" => (int)$price->open,
                        "high" => (int)$price->high,
                        "low" => (int)$price->low,
                        "close" => (int)$price->close,
                        "volume" => (int)$price->volume,
                        "time" => (int)Carbon::make($price->time)->unix(),
                    ];
                });
        });
    }
}