<?php

uses(RefreshDatabase::class);

test('invoke returns an ok response', function () {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $block = \App\Models\Block::factory()->create();

    $response = $this->post(route('search'), [
        // TODO: send request data
    ]);

    $response->assertRedirect(route('home'));

    // TODO: perform additional assertions
});

// test cases...