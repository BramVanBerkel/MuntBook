<?php

use App\Services\BlockService;

beforeEach(function () {
    $this->blockService = app(BlockService::class);
});

test('it calculates the correct block subsidies', function (int $height, float $subsidy, string $type) {
    expect($subsidy)->toBe($this->blockService->getBlockSubsidy($height)->{$type}());
})->with([
    [1, 170_000_000.0, 'mining'],
    [250000, 1000.0, 'mining'],
    [250001, 100.0, 'mining'],
    [1_030_001, 110.0, 'total'],
    [1131652, 110.0, 'total'],
    [1131652 + 1, 120.0, 'total'],
    [1_226_651, 120.0, 'total'],
    [1_226_652, 200.0, 'total'],
    [1_226_652, 90.0, 'mining'],
    [1_226_652, 80.0, 'development'],
    [1_226_652, 30.0, 'witness'],
    [1_228_003, 200.0, 'total'],
    [1_228_004, 160.0, 'total'],
    [1_228_004, 50.0, 'mining'],
    [1_228_004, 80.0, 'development'],
    [1_228_004, 30.0, 'witness'],
    [1_400_000, 50.0, 'mining'],
    [1_400_000, 80.0, 'development'],
    [1_400_000, 30.0, 'witness'],
    [1_400_001, 10.0, 'mining'],
    [1_400_001, 15.0, 'witness'],
    [1_400_001, 65.0, 'development'],
    [2_242_500, 10.0, 'mining'],
    [2_242_500, 15.0, 'witness'],
    [2_242_500, 65.0, 'development'],
    [2_242_501, 5.0, 'mining'],
    [2_242_501, 7.50, 'witness'],
    [2_242_501, 32.5, 'development'],
    [17_727_501, 0.0, 'total'],
]);
