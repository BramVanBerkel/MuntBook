<?php

test('invoke returns an ok response', function () {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $address = \App\Models\Address::factory()->create();

    $response = $this->get(route('address', [$address]));

    $response->assertOk();

    // TODO: perform additional assertions
});

// test cases...
