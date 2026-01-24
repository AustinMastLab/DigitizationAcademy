<?php

/*
 * Copyright (C) 2022 - 2026, Digitization Academy
 * idigacademy@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

it('contact page can be rendered', function () {
    $response = $this->get('/contact');

    $response->assertStatus(200);
});

it('can submit contact form with honeypot', function () {
    Mail::fake();

    $honeypotFields = getHoneypotFields('/contact');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->from('/contact')->post('/contact', array_merge([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, this is a test message.',
    ], $honeypotFields));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/contact');
    $response->assertSessionHas('toast_success', 'Contact message sent successfully.');

    Mail::assertQueued(Contact::class);
});

it('cannot submit contact form if honeypot is tripped', function () {
    Mail::fake();

    $honeypotFields = getHoneypotFields('/contact');
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->from('/contact')->post('/contact', array_merge([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, this is a test message.',
    ], $honeypotFields));

    $response->assertSessionHasErrors(['contact']);
    Mail::assertNothingQueued();
});

it('cannot submit contact form if submitted too fast', function () {
    Mail::fake();

    $honeypotFields = getHoneypotFields('/contact');

    $response = $this->from('/contact')->post('/contact', array_merge([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, this is a test message.',
    ], $honeypotFields));

    $response->assertSessionHasErrors(['contact']);
    Mail::assertNothingQueued();
});
