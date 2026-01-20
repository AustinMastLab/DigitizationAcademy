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

return [
    'server_user' => env('SERVER_USER', 'ubuntu'),
    'course_image_dir' => 'course_image',
    'course_syllabus_dir' => 'course_syllabus',
    'team_image_dir' => 'team_image',
    'idigbio_event_calendar' => env('IDIGBIO_EVENT_CALENDAR'),
    'google_course_calender_id' => env('GOOGLE_COURSE_CALENDER_ID', 'fb5fc3d0305f68b4df084c50b35cfbea445ce43202a6e4dc9ffaa5ee6d7bfd5e@group.calendar.google.com'),
    'google_credential_file' => env('GOOGLE_CREDENTIAL_FILE', 'app/google-calendar/google-auth.json'),

    'course_type' => [
        '2_hour' => 1,
        '12_hour' => 2,
    ],
];
