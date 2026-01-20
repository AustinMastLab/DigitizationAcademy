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

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateQueries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-queries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database update queries and migrations';

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        if (\Storage::exists('app/google-calendar/digitization-academy-b46a90f10ffb.json')) {
            \Storage::delete('app/google-calendar/digitization-academy-b46a90f10ffb.json');
        }
    }
}
