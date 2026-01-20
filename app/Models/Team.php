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

namespace App\Models;

use App\Traits\ClearsResponseCache;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use ClearsResponseCache, HasFactory, Presentable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'email',
        'about',
        'image',
        'order',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Set order for new records
        static::creating(function ($team) {
            if (is_null($team->order)) {
                $team->order = static::max('order') + 1;
            }
        });

        // Listen for database queries that might be reordering operations
        static::bootedIfNotBooted(function () {
            \Illuminate\Support\Facades\DB::listen(function ($query) {
                // Check if this is an UPDATE query on the teams table involving the order column
                if (stripos($query->sql, 'update') === 0 &&
                    stripos($query->sql, 'teams') !== false &&
                    stripos($query->sql, 'order') !== false) {

                    // Clear all cache (equivalent to artisan cache:clear)
                    \Illuminate\Support\Facades\Cache::flush();
                }
            });
        });
    }

    /**
     * Ensure the database listener is only registered once
     */
    protected static function bootedIfNotBooted(callable $callback): void
    {
        static $booted = [];

        $class = static::class;
        if (! isset($booted[$class])) {
            $callback();
            $booted[$class] = true;
        }
    }
}
