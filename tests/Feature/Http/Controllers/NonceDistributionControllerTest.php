<?php

test('invoke returns an ok response', function () {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $response = $this->get(route('nonce-distribution'));

    $response->assertOk();
    $response->assertViewIs('pages.nonce-distribution');

    // TODO: perform additional assertions
});

// test cases...
