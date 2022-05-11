<?php

use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function () {
    $this->markTestSkipped('registering is disabled');
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $this->markTestSkipped('registering is disabled');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});
