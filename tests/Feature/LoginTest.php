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

use App\Models\User;
use App\Providers\RouteServiceProvider;

it('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('can login with honeypot', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/login');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/login', array_merge([
        'email' => $user->email,
        'password' => 'password',
    ], $honeypotFields));

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(RouteServiceProvider::HOME);
});

it('cannot login if honeypot is tripped', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/login');
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->post('/login', array_merge([
        'email' => $user->email,
        'password' => 'password',
    ], $honeypotFields));

    $this->assertGuest();
    $response->assertSessionHasErrors(['contact']);
});

it('cannot login if submitted too fast', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/login');

    $response = $this->post('/login', array_merge([
        'email' => $user->email,
        'password' => 'password',
    ], $honeypotFields));

    $this->assertGuest();
    $response->assertSessionHasErrors(['contact']);
});
