<?php

/*
 * Copyright (c) 2022. Digitization Academy
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

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CourseService
{
    /**
     * Get course with related data for display
     */
    public function getCourseForDisplay(string $slug): Course
    {
        return Course::slug($slug)->active()->with('assets')->with([
            'events' => function ($query) {
                $query->whereDate('start_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->orderBy('start_date', 'asc');
            },
        ])->firstOrFail();
    }

    /**
     * Check if registration/application button should be shown
     */
    public function shouldShowRegistrationButton(Course $course): bool
    {
        if (! $course->events?->isNotEmpty()) {
            return false;
        }

        $firstEvent = $course->events->first();

        return Carbon::now()->between(
            $firstEvent->form_start_date,
            $firstEvent->form_end_date
        );
    }

    /**
     * Generate next offering text for course display
     */
    public function generateNextOffering(Course $course): string
    {
        $hasEvents = $course->events?->isNotEmpty() ?? false;
        $nextOffering = 'Next offering: ';

        // Assumes course type 1 is a 2-hour course
        return match ([$course->course_type_id, $hasEvents]) {
            [config('config.course_type.2_hour'), true] => $nextOffering.$course->events->first()->start_date->format('F j, Y'),
            [config('config.course_type.2_hour'), false] => 'Course concluded.',
            default => $nextOffering.($hasEvents
                ? $this->formatMultiCourseOffering($course->events)
                : 'to be announced')
        };
    }

    /**
     * Check if offerings pane should be shown
     */
    public function shouldShowOfferingsPane(Course $course): bool
    {
        return $course->events && $course->events->isNotEmpty();
    }

    /**
     * Check if offerings tab should be active
     * Assumes course type 1 is a 2-hour course
     */
    public function isOfferingsTabActive(Course $course): bool
    {
        return $course->course_type_id === 1 && $this->shouldShowOfferingsPane($course);
    }

    /**
     * Format offering text for multi-session courses
     */
    private function formatMultiCourseOffering(Collection $events): string
    {
        return match ($events->count()) {
            0 => (string) Carbon::now()->addYear()->year,
            1 => $events->first()->start_date->format('F j, Y'),
            default => sprintf('%s - %s',
                $events->first()->start_date->format('F j, Y'),
                $events->last()->start_date->format('F j, Y')
            ),
        };
    }
}
