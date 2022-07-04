<?php

use App\Models\Block;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('it returns the correct format', closure: function () {
    /**
     * Create 100 blocks evenly distributed over 10 days.
     * To do this we must space them apart by 8640 seconds.
     */


    $startDate = Carbon::create(2022, 01, 01);

    // to make sure the first date is 2022-01-01 00:00:00
    $startDate->subSeconds(8640);

    $blocks = Block::factory()
        ->count(100)
        ->state(function() use($startDate) {
            return [
                'created_at' => $startDate->addSeconds(8640),
            ];
        })
        ->create();

    $dates = $blocks->pluck('created_at')
        ->unique(fn(Carbon $date) => $date->startOfDay())
        ->transform(fn(Carbon $date) => $date->toISOString())
        ->values();

    $averageBlockTimeSeconds = collect()->pad(10, 8640);

    $blocksPerDay = collect()->pad(10, 10);

    $this->getJson(route('api.average-blocktime'))
        ->assertOk()
        ->assertJsonCount(10, 'averageBlockTimes')
        ->assertJsonCount(10, 'blocksPerDay')
        ->assertJsonPath('averageBlockTimes.*.date', $dates->toArray())
        ->assertJsonPath('averageBlockTimes.*.seconds', $averageBlockTimeSeconds->toArray())
        ->assertJsonPath('blocksPerDay.*.date', $dates->toArray())
        ->assertJsonPath('blocksPerDay.*.blocks', $blocksPerDay->toArray());
});
