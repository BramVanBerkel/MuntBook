<?php

use App\Services\BlockService;

beforeEach(function () {
    $this->blockService = app(BlockService::class);
});

test('it calculates the correct block subsidies', function (int $height, float $subsidy, string $type) {
    expect($subsidy)->toBe($this->blockService->getBlockSubsidy($height)->{$type}());
})->with([
    [1, 170000000, 'mining'],
    [250000, 1000, 'mining'],
    [250001, 100, 'mining'],
    [1030001, 110, 'total'], // pow2_phase_4_first_block_height
    [1131652, 110, 'total'],
    [1131652 + 1, 120, 'total'],
    [1226651, 120, 'total'],
    [1226652, 200, 'total'],
    [1226652, 90, 'mining'],
    [1226652, 80, 'development'],
    [1226652, 30, 'witness'],
    [1228003, 200, 'total'],
    [1228004, 160, 'total'],
    [1228004, 50, 'mining'],
    [1228004, 80, 'development'],
    [1228004, 30, 'witness'],
    [1400000, 50, 'mining'],
    [1400000, 80, 'development'],
    [1400000, 30, 'witness'],
    [1400001, 10, 'mining'],
    [1400001, 15, 'witness'],
    [1400001, 65, 'development'],
    [1619997, 10, 'mining'],
    [1619997, 15, 'witness'],
    [1619997, 100000000, 'development'],
    [1619998, 10, 'mining'],
    [1619998, 15, 'witness'],
    [1619998, 0, 'development'],
    [2242500, 5, 'mining'],
    [2242500, 7.50, 'witness'],
    [2242500, 0, 'development'],
    [2242501, 5, 'mining'],
    [2242501, 7.50, 'witness'],
    [2242501, 0, 'development'],
    [433009989, 0, 'mining'],
    [433009989, 0, 'witness'],
    [433009989, 0, 'development'],
]);
