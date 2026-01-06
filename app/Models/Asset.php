<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use App\Traits\FixesHydration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asset extends Model
{
    use ClearsResponseCache, FixesHydration, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
    ];

    protected static function boot()
    {
        parent::boot();

        // Set a default order when the model is queried
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('name');
        });
    }

    /**
     * The courses that belong to the resource.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Save the model to the database.
     *
     * Fix for cases where auto-increment ID is not populated after save (e.g., Laravel 12 hydration edge cases).
     */
    public function save(array $options = []): bool
    {
        $result = parent::save($options);

        // If save succeeded, it's a fresh insert, auto-increment is enabled, but key is still null—fetch it manually
        if ($result && $this->wasRecentlyCreated && $this->getIncrementing() && is_null($this->getKey())) {
            $lastId = $this->getConnection()->getPdo()->lastInsertId();
            if ($lastId !== '0' && $lastId !== 0) {  // Guard against invalid/zero IDs
                $this->setAttribute($this->getKeyName(), $lastId);
                $this->syncOriginalAttribute($this->getKeyName());  // Sync to avoid "dirty" flags later
                $this->syncChanges();  // Optional: Refresh changed attributes
            }
        }

        return $result;
    }
}
