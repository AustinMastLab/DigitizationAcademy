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

use Illuminate\Support\Facades\Mail;
use Spatie\ResponseCache\Facades\ResponseCache;

beforeEach(function () {
    config([
        'responsecache.enabled' => true,
        'responsecache.debug.enabled' => true,
    ]);

    ResponseCache::clear();
});

it('serves a public page from the response cache on the second request', function () {
    $statusHeader = config('responsecache.debug.cache_status_header_name');

    $this->get(route('home'))
        ->assertOk()
        ->assertHeader($statusHeader, 'MISS');

    $this->get(route('home'))
        ->assertOk()
        ->assertHeader($statusHeader, 'HIT');
});

it('does not serve cached pages to livewire requests', function () {
    $statusHeader = config('responsecache.debug.cache_status_header_name');

    $this->get(route('home'));

    $this->withHeader('X-Livewire', 'true')
        ->get(route('home'))
        ->assertOk()
        ->assertHeaderMissing($statusHeader);
});

it('shows the contact toast after redirecting to a cached contact page', function () {
    Mail::fake();
    $honeypotFields = getHoneypotFields('/contact');
    $this->get('/contact')->assertHeader(config('responsecache.debug.cache_status_header_name'), 'HIT');
    $this->travel(config('honeypot.amount_of_seconds') + 1)->seconds();

    $this->followingRedirects()->from('/contact')->post('/contact', array_merge([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, this is a test message.',
    ], $honeypotFields))
        ->assertOk()
        ->assertSee('Contact message sent successfully.');

    $this->get('/contact')->assertDontSee('Contact message sent successfully.');
});
