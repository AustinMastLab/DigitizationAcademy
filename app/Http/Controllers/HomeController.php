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

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Display the application's home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable The rendered home view
     */
    public function index(): Renderable
    {
        return view('home');
    }

    /**
     * Reset the PHP OpCache.
     */
    public function resetOpCache(Request $request)
    {
        $token = $request->input('token');
        $validToken = config('app.opcache_webhook_token');

        if (empty($validToken) || $token !== $validToken) {
            return Response::json(['message' => 'Unauthorized'], 403);
        }

        if (function_exists('opcache_reset')) {
            if (opcache_reset()) {
                return Response::json(['message' => 'OpCache reset successful'], 200);
            }

            return Response::json(['message' => 'OpCache reset failed'], 500);
        }

        return Response::json(['message' => 'OpCache not available'], 500);
    }
}
