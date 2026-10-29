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

namespace App\Http\CacheProfiles;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

/**
 * Caches successful GET requests, except while the session carries flash data.
 *
 * Flash data (toast messages, validation errors, old input) is rendered into
 * the page, so such a request must neither be served from the cache nor be
 * stored in it.
 */
class SkipCacheWhenSessionHasFlash extends CacheAllSuccessfulGetRequests
{
    public function enabled(Request $request): bool
    {
        return parent::enabled($request) && ! $this->hasFlashData($request);
    }

    protected function hasFlashData(Request $request): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        $session = $request->session();

        return ! empty($session->get('_flash.old')) || ! empty($session->get('_flash.new'));
    }
}
