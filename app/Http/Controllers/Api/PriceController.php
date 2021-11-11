<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    public function index(string $timeframe)
    {
        $since = match($timeframe) {
            '1d' => now()->subDay(),
            '7d' => now()->subDays(7),
            '1m' => now()->subMonth(),
            '3m' => now()->subMonths(3),
            'ytd' => now()->startOfYear(),
            'all' => null,
        };

        $groupBy = match($timeframe) {
            '1d' => 300,
            '7d' => 600,
            '1m' => 60,
            '3m' => 3600,
            'ytd' => 28800,
            'all' => null,
        };

        $since = Carbon::create(2021, 10, 30);

        return Price::query()
            ->whereDate('timestamp', '=', $since)
            ->select([
                DB::raw("TIMESTAMP 'epoch' + INTERVAL '1 second' * round(extract('epoch' from timestamp) / $groupBy) * $groupBy as x"),
                DB::raw("avg(price)::integer as y"),
            ])
            ->groupBy('x')
            ->orderBy('x')
            ->get();
    }
}
