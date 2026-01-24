<?php

use App\Models\Course;
use App\Models\CourseType;

it('has home page')->get('/')->assertStatus(200);

it('has catalog page')->get('/catalog')->assertStatus(200);

it('has catalog all page')->get('/catalog/all')->assertStatus(200);

it('has catalog past page')->get('/catalog/past')->assertStatus(200);

it('has catalog upcoming page')->get('/catalog/upcoming')->assertStatus(200);

it('has calendar page')->get('/calendar')->assertStatus(200);

it('has team page')->get('/team')->assertStatus(200);

it('has contact page')->get('/contact')->assertStatus(200);

it('has register-retry page')->get('/register-retry')->assertRedirect('/');

it('has course slug page', function () {
    $courseType = CourseType::factory()->create();
    $course = Course::factory()->create([
        'course_type_id' => $courseType->id,
        'slug' => 'test-course',
        'active' => true,
    ]);

    $this->get('/course/'.$course->slug)->assertStatus(200);
});
