<?php

namespace App\Http\Controllers\Api;

use App\DataObjects\PriceData;
use App\Enums\PriceTimeframeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceCollection;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    public function index(PriceTimeframeEnum $timeframe)
    {
        $prices = Cache::remember("prices-{$timeframe->value}", $timeframe->since(), function () use ($timeframe) {
            $query = DB::table('prices')
                ->select([
                    DB::raw("TIMESTAMP 'epoch' + INTERVAL '1 second' * ROUND(EXTRACT('epoch' FROM timestamp) / {$timeframe->tickSize()}) * {$timeframe->tickSize()} as time"),
                    DB::raw('AVG(price) AS value'),
                ])
                ->when($timeframe->since() !== null, function (Builder $query) use ($timeframe) {
                    $query->whereDate('timestamp', '>=', $timeframe->since());
                })
                ->groupBy('time')
                ->orderBy('time');

            return $query
                ->get()
                ->map(fn (object $price): PriceData => new PriceData(
                    timestamp: new Carbon($price->time),
                    value: $price->value,
                ));
        });

        return PriceCollection::make($prices);
    }
}
