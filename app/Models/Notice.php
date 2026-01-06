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
use App\Traits\FixesHydration;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notice
 */
class Notice extends Model
{
    use ClearsResponseCache, FixesHydration;

    /**
     * {@inheritDoc}
     */
    protected $table = 'notices';

    protected $fillable = [
        'message',
        'enabled',
    ];
}
