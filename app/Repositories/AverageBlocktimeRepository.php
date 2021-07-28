<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AverageBlocktimeRepository
{
    public function getAverageBlocktime(): Collection
    {
        return Cache::remember('average-blocktime', now()->addHour(), function() {
            return DB::query()->select([
                'day',
                DB::raw('LEAST(EXTRACT(EPOCH FROM avg(delta)), 200) as seconds')])
                ->fromSub(DB::table('blocks')
                    ->select([
                        DB::raw('DATE_TRUNC(\'day\', created_at) AS "day"'),
                        DB::raw('created_at - LAG(created_at, 1) OVER (ORDER BY created_at) AS "delta"')
                    ])
                    ->where('created_at', '>', now()->subYear())
                    ->groupBy([
                        'day',
                        'created_at'
                    ]), 'groups')
                ->groupBy('groups.day')
                ->orderByDesc('groups.day')
                ->get();
        });
    }

    public function getBlocksPerDay()
    {
        return Cache::remember('blocks-per-day', now()->addHour(), function() {
            return DB::table('blocks')
                ->select([
                    DB::raw('DATE_TRUNC(\'day\', created_at) AS day'),
                    DB::raw('COUNT(*) AS blocks')
                ])
                ->where('created_at', '>', now()->subYear()->endOfDay())
                ->where('created_at', '<', now()->startOfDay())
                ->groupBy('day')
                ->orderByDesc('day')
                ->get();
        });
    }
}

