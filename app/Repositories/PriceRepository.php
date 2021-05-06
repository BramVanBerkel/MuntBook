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

    public function getPrices(PriceTimeframeEnum $timeframe, string $source)
    {
        //TODO: move to enum or seperate class
        $seconds = match ($timeframe->value) {
            PriceTimeframeEnum::MINUTE_1()->value => 60,
            PriceTimeframeEnum::MINUTE_5()->value => 300,
            PriceTimeframeEnum::MINUTE_15()->value => 900,
            PriceTimeframeEnum::MINUTE_30()->value => 1800,
            PriceTimeframeEnum::HOUR_1()->value => 3600,
            PriceTimeframeEnum::HOUR_2()->value => 7200,
            PriceTimeframeEnum::HOUR_3()->value => 10800,
            PriceTimeframeEnum::HOUR_4()->value => 14400,
            PriceTimeframeEnum::DAY_1()->value => 86400,
            PriceTimeframeEnum::DAY_7()->value => 604800,
            PriceTimeframeEnum::DAY_30()->value => 2592000,
        };

        return Cache::remember("prices_{$timeframe}_{$source}", now()->addSeconds($seconds), function() use($seconds, $source) {
            return DB::table('prices')
                ->selectRaw('(MAX(ARRAY[id, open]))[2] AS open')
                ->selectRaw('MAX(high) AS high')
                ->selectRaw('MIN(low) AS low')
                ->selectRaw('(MIN(ARRAY[id, close]))[2] AS close')
                ->selectRaw('SUM(volume) as volume')
                ->selectRaw('TO_TIMESTAMP(FLOOR((EXTRACT(\'epoch\' FROM timestamp) / ? )) * ?) AS time', [$seconds, $seconds])
                ->where('source', '=', $source)
                ->where('timestamp', '>', now()->subSeconds($seconds * 14400))
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
                        "time" => Carbon::make($price->time)->unix(),
                    ];
                });
        });
    }
}
