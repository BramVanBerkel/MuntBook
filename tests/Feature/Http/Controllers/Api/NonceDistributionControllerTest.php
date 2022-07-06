<?php

use App\Models\Block;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('it returns the correct nonce distribution', function () {
    $blocks = Block::factory()
        ->count(10)
        ->create();

    $preNonceData = $blocks
        ->sortByDesc('height')
        ->map(fn (Block $block) => [
            'x' => $block->height,
            'y' => $block->pre_nonce,
        ])
        ->values();

    $postNonceData = $blocks
        ->sortByDesc('height')
        ->map(fn (Block $block) => [
            'x' => $block->height,
            'y' => $block->post_nonce,
        ])
        ->values();

    $this->getJson('api/nonce-distribution')
        ->assertJsonPath('preNonceData', $preNonceData->toArray())
        ->assertJsonPath('postNonceData', $postNonceData->toArray());
});

// test cases...
