<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;

it('can render reset password link screen', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');

it('can request reset password link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/forgot-password');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/forgot-password', array_merge([
        'email' => $user->email,
    ], $honeypotFields));

    Notification::assertSentTo($user, ResetPassword::class);
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');

it('cannot request reset password link if honeypot is tripped', function () {
    Notification::fake();

    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/forgot-password');
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/forgot-password', array_merge([
        'email' => $user->email,
    ], $honeypotFields));

    Notification::assertNotSentTo($user, ResetPassword::class);
    $response->assertSessionHasErrors(['contact']);
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');

it('can render reset password screen', function () {
    Notification::fake();

    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/forgot-password');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/forgot-password', array_merge([
        'email' => $user->email,
    ], $honeypotFields));

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');

it('can reset password with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/forgot-password');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/forgot-password', array_merge([
        'email' => $user->email,
    ], $honeypotFields));

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $honeypotFields = getHoneypotFields('/reset-password/'.$notification->token);
        $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

        $response = $this->post('/reset-password', array_merge([
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $honeypotFields));

        $response->assertSessionHasNoErrors();

        return true;
    });
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');

it('cannot reset password if honeypot is tripped', function () {
    Notification::fake();

    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/forgot-password');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/forgot-password', array_merge([
        'email' => $user->email,
    ], $honeypotFields));

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $honeypotFields = getHoneypotFields('/reset-password/'.$notification->token);
        $randomFieldName = array_key_first($honeypotFields);
        $honeypotFields[$randomFieldName] = 'spam';
        $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

        $response = $this->post('/reset-password', array_merge([
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $honeypotFields));

        $response->assertSessionHasErrors(['contact']);

        return true;
    });
})->skip(function () {
    return ! Features::enabled(Features::resetPasswords());
}, 'Password updates are not enabled.');
