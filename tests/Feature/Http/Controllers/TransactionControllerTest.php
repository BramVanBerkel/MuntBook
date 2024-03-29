<?php

test('invoke returns an ok response', function () {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $transaction = \App\Models\Transaction::factory()->create();

    $response = $this->get(route('transaction', ['txid' => $transaction->txid]));

    $response->assertOk();
    $response->assertViewIs('pages.transaction');
    $response->assertViewHas('transaction', $transaction);
    $response->assertViewHas('outputs');
    $response->assertViewHas('fee');

    // TODO: perform additional assertions
});

// test cases...
