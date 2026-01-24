<?php

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Features;

it('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

it('cannot rendered registration screen if support is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
})->skip(function () {
    return Features::enabled(Features::registration());
}, 'Registration support is enabled.');

it('can register new users', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'Staff']);

    $honeypotFields = getHoneypotFields('/register');

    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/register', array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $honeypotFields));

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

it('cannot register users if honeypot is tripped', function () {
    $honeypotFields = getHoneypotFields('/register');
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';

    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/register', array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $honeypotFields));

    $this->assertGuest();
    $response->assertSessionHasErrors(['contact']);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

it('cannot register users if submitted too fast', function () {
    $honeypotFields = getHoneypotFields('/register');

    $response = $this->post('/register', array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $honeypotFields));

    $this->assertGuest();
    $response->assertSessionHasErrors(['contact']);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');
