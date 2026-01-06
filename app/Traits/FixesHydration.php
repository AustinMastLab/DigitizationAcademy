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

namespace App\Traits;

trait FixesHydration
{
    /**
     * Save the model to the database.
     *
     * Fix for rare cases where auto-increment ID is not populated after insert
     * (e.g., Laravel hydration edge cases with custom traits or DB configs).
     */
    public function save(array $options = []): bool
    {
        $result = parent::save($options);

        // If insert succeeded, auto-increment is enabled, but key is still null—fetch manually
        if ($result && $this->wasRecentlyCreated && $this->getIncrementing() && is_null($this->getKey())) {
            $lastId = $this->getConnection()->getPdo()->lastInsertId();
            if ($lastId !== '0' && $lastId !== 0) {  // Guard against invalid/zero IDs
                $this->setAttribute($this->getKeyName(), $lastId);
                $this->syncOriginalAttribute($this->getKeyName());  // Sync to avoid "dirty" flags
                $this->syncChanges();  // Refresh changed attributes if needed
            }
        }

        return $result;
    }
}
