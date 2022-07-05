<?php

use App\Models\Block;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('it returns the circulating supply', function () {
    Block::factory()
        ->create([
            'height' => 1_550_100,
        ]);

    $this->getJson(route('api.circulating-supply'))
        ->assertSee(561879610);
});

test('it returns the total supply', function () {
    $this->getJson('api/total-supply')
        ->assertSee(700000000);
});
