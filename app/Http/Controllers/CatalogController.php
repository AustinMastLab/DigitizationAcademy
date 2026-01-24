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

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\View\View;

/**
 * Controller for handling catalog-related functionality.
 * Manages the display of course catalog and filtering by course type.
 */
class CatalogController extends Controller
{
    /**
     * Display the main catalog page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('catalog');
    }

    /**
     * Display courses filtered by type.
     *
     * @param  EventService  $service  Service for retrieving course events
     * @param  string  $type  The type of courses to display ('past', 'all', 'upcoming')
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(EventService $service, string $type): View
    {
        // Validate that the type is one of the allowed values
        if (! in_array($type, ['past', 'all', 'upcoming'])) {
            abort(404);
        }

        $events = $service->showCatalogCourses($type);

        return view('partials.catalog-events', compact('events', 'type'));
    }
}
