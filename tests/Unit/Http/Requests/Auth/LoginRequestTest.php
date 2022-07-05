<?php

beforeEach(function () {
    $this->subject = new \App\Http\Requests\Auth\LoginRequest();
});

test('authorize', function () {
    $actual = $this->subject->authorize();

    $this->assertTrue($actual);
});

test('rules', function () {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'email' => [
            'required',
            'string',
            'email',
        ],
        'password' => [
            'required',
            'string',
        ],
    ], $actual);
});

// test cases...
