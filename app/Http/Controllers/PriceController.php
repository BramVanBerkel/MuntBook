<?php

namespace App\Http\Controllers;

use App\Models\BittrexPrices;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    public function index()
    {
        return view('layouts.pages.prices');
    }

    public function data()
    {
        return cache()->remember('bittrex_prices', now()->addHour(), function() {
            return DB::table('prices')->select([
                DB::raw('(MAX(ARRAY[id, open]))[2] as open'),
                DB::raw('MAX(high) as high'),
                DB::raw('MAX(low) as low'),
                DB::raw('(MIN(ARRAY[id, close]))[2] AS close'),
                DB::raw('EXTRACT(epoch FROM date_trunc(\'hour\', timestamp)) AS time'),
            ])->where('source', '=', Price::SOURCE_BITTREX)
                ->groupBy('time')
                ->orderBy('time')
                ->where('timestamp', '>', now()->subYear())
                ->get()
                ->map(function($price) {
                    return [
                        "open" => (int)$price->open,
                        "high" => (int)$price->high,
                        "low" => (int)$price->low,
                        "close" => (int)$price->close,
                        "time" => (int)$price->time,
                    ];
                });
        });
    }
}
