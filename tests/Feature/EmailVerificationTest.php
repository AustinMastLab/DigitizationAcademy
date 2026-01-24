<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;

it('can render email verification screen', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get('/email/verify');

    $response->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::emailVerification());
}, 'Email verification not enabled.');

it('can verify email', function () {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
})->skip(function () {
    return ! Features::enabled(Features::emailVerification());
}, 'Email verification not enabled.');

it('can verify email with invalid hash', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user->fresh())->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
})->skip(function () {
    return ! Features::enabled(Features::emailVerification());
}, 'Email verification not enabled.');

it('can resend email verification notification', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $honeypotFields = getHoneypotFields('/email/verify', $user);
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->actingAs($user)->from('/email/verify')->post('/email/verification-notification', $honeypotFields);

    Notification::assertSentTo($user, App\Notifications\VerifyEmailQueued::class);
    $response->assertRedirect('/email/verify');
})->skip(function () {
    return ! Features::enabled(Features::emailVerification());
}, 'Email verification not enabled.');

it('cannot resend email verification notification if honeypot is tripped', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $honeypotFields = getHoneypotFields('/email/verify');
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->actingAs($user)->post('/email/verification-notification', $honeypotFields);

    Notification::assertNotSentTo($user, Illuminate\Auth\Notifications\VerifyEmail::class);
    $response->assertSessionHasErrors(['contact']);
})->skip(function () {
    return ! Features::enabled(Features::emailVerification());
}, 'Email verification not enabled.');
