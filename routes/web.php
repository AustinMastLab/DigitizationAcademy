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

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// OpCache Reset Route (Token passed in POST body)
Route::post('/opcache/reset', [HomeController::class, 'resetOpCache'])->name('opcache.reset');

Route::get('catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('catalog/{type}', [CatalogController::class, 'show'])
    ->name('catalog.show')
    ->whereIn('type', ['past', 'all', 'upcoming']);
Route::get('course/{slug}', CourseController::class)->name('course.index');
Route::get('calendar', CalendarController::class)->name('calendar.index');
Route::get('team', TeamController::class)->name('team.index');
Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('contact', [ContactController::class, 'store'])
    ->middleware(ProtectAgainstSpam::class)->name('contact.store');
Route::get('/register-retry', function () {
    // Chrome F12 Headers - my_first_application_session=eyJpdiI6ImNnRH...
    $all_cookies = Cookie::get();
    foreach ($all_cookies as $name => $value) {
        Cookie::queue(Cookie::forget($name));
    }

    return redirect('/');
})->name('register-retry');
