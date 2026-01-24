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

it('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/user/confirm-password');

    $response->assertStatus(200);
});

it('can confirm password with honeypot', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/user/confirm-password', $user);
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->actingAs($user)->post('/user/confirm-password', array_merge([
        'password' => 'password',
    ], $honeypotFields));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
});

it('cannot confirm password if honeypot is tripped', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/user/confirm-password', $user);
    $randomFieldName = array_key_first($honeypotFields);
    $honeypotFields[$randomFieldName] = 'spam';
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $response = $this->actingAs($user)->post('/user/confirm-password', array_merge([
        'password' => 'password',
    ], $honeypotFields));

    $response->assertSessionHasErrors(['contact']);
});

it('cannot confirm password if submitted too fast', function () {
    $user = User::factory()->create();

    $honeypotFields = getHoneypotFields('/user/confirm-password', $user);

    $response = $this->actingAs($user)->post('/user/confirm-password', array_merge([
        'password' => 'password',
    ], $honeypotFields));

    $response->assertSessionHasErrors(['contact']);
});
