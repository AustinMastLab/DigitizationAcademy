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

namespace App\Traits;

/**
 * Trait for adding presenter functionality to models.
 *
 * This trait provides a convenient way to associate models with their corresponding
 * presenter classes. It automatically resolves and instantiates the appropriate
 * presenter based on the model's class name.
 */
trait Presentable
{
    /**
     * Creates and returns a presenter instance for the model.
     *
     * This method dynamically constructs the presenter class name by appending 'Presenter'
     * to the model's base class name and instantiates it with the current model instance.
     *
     * @return mixed The corresponding presenter instance for the model
     */
    public function present(): mixed
    {
        $presenterClass = 'App\\Presenters\\'.class_basename($this).'Presenter';

        return new $presenterClass($this);
    }
}
