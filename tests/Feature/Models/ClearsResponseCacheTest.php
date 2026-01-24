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

use App\Models\Asset;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Team;
use App\Models\User;
use Spatie\ResponseCache\Facades\ResponseCache;

beforeEach(function () {
    config(['responsecache.enabled' => true]);
});

it('clears response cache when an asset is created', function () {
    ResponseCache::spy();
    Asset::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a course type is created', function () {
    ResponseCache::spy();
    CourseType::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a notice is created', function () {
    ResponseCache::spy();
    Notice::create(['message' => 'Test notice', 'enabled' => true]);
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a team is created', function () {
    ResponseCache::spy();
    Team::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a user is created', function () {
    ResponseCache::spy();
    User::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a course is created', function () {
    ResponseCache::spy();
    Course::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when an event is created', function () {
    ResponseCache::spy();
    Event::factory()->create();
    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a model using the trait is updated', function () {
    $course = Course::factory()->create();

    ResponseCache::spy();
    $course->update(['title' => 'Updated Title']);

    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('clears response cache when a model using the trait is deleted', function () {
    $course = Course::factory()->create();

    ResponseCache::spy();
    $course->delete();

    ResponseCache::shouldHaveReceived('clear')->atLeast()->once();
});

it('does not clear response cache when disabled', function () {
    config(['responsecache.enabled' => false]);
    ResponseCache::spy();

    Course::factory()->create();

    ResponseCache::shouldNotHaveReceived('clear');
});
