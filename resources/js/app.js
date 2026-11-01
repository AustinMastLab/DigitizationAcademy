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

import './bootstrap';
import './vendor/bootstrap.bundle.min';
import './vendor/jquery.nav';
import './vendor/jquery-appear';
import './vendor/tilt';
import './main';
import './google-translate';

// Let Vite version the images Blade references with Vite::asset().
// Eager ?url imports, because Vite 8 drops an unused lazy glob from the build.
import.meta.glob('../images/**', { eager: true, query: '?url', import: 'default' });
