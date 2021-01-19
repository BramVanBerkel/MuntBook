<?php


namespace App\Repositories;


use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HashrateRepository
{
    public function getHashrate(int $timeframe, int $average): Collection
    {
        // -1 because we already include the current row.
        $average--;

        $from = ($timeframe > 0) ? now()->subDays($timeframe) : Carbon::createFromTimestamp(0);

        return DB::table(function (Builder $query) use ($from) {
            $query->select(DB::raw("date_trunc('day', created_at) AS date"),
                DB::raw("avg(hashrate) as hashrate"))
                ->from('blocks')
                ->whereBetween('created_at', [$from, now()])
                ->groupBy('date')
                ->orderBy('date');
        }, 'average')
            ->select('average.date as date',
                DB::raw("AVG(average.hashrate) OVER(ORDER BY average.date ROWS BETWEEN {$average} PRECEDING AND CURRENT ROW) AS average_hashrate"))
            ->get();
    }
}
