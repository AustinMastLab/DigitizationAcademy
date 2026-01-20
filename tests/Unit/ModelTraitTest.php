<?php

use App\Models\Asset;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Team;
use App\Models\User;
use App\Traits\ClearsResponseCache;

test('models have ClearsResponseCache trait', function () {
    expect(class_uses_recursive(Asset::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(Course::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(CourseType::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(Event::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(Notice::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(Team::class))->toContain(ClearsResponseCache::class)
        ->and(class_uses_recursive(User::class))->toContain(ClearsResponseCache::class);
})->skip('Dependencies in migrations are broken in test environment');
