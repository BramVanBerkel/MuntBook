<?php

use App\Enums\PriceTimeframeEnum;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

//uses(LazilyRefreshDatabase::class);

test('it returns the correct format', function () {
    $timestamp = Carbon::create(2022, 1, 1, 12, 0);

    Carbon::setTestNow($timestamp);

    Price::factory()
        ->state(new Sequence(
            ['timestamp' => $timestamp, 'price' => 0.2],
        ))
        ->create();

    $this->getJson(route('api.prices', [
        'timeframe' => PriceTimeframeEnum::ONE_DAY->value
    ]))
        ->assertJsonPath('0.time', $timestamp->getTimestamp())
        ->assertJsonPath('0.value', 0.2);
});

test('it returns the average price', function() {
    $timestamp = Carbon::create(2022, 1, 1, 12, 0);

    Carbon::setTestNow($timestamp);

    Price::factory()
        ->state(new Sequence(
            ['timestamp' => $timestamp, 'price' => 0.2],
            ['timestamp' => $timestamp->addMinute(), 'price' => 0.3],
            ['timestamp' => $timestamp->addMinutes(2), 'price' => 0.5],
            ['timestamp' => $timestamp->addMinutes(3), 'price' => 0.7],
            ['timestamp' => $timestamp->addMinutes(4), 'price' => 0.9],
        ))
        ->count(5)
        ->create();

    $this->getJson(route('api.prices', [
        'timeframe' => PriceTimeframeEnum::ONE_DAY->value
    ]))
        ->assertJsonPath('0.time', $timestamp->getTimestamp())
        ->assertJsonPath('0.value', 0.52);
});
