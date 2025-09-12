<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = [
        'bundle_id',
        'name',
        'location',
        'description',
        'main_image',
        'included_items',
        'restrictions',
        'gallery',
        'destination_type',
        'display_in_custom_bundles'
    ];

    protected $casts = [
        'included_items' => 'array',
        'gallery' => 'array',
        'display_in_custom_bundles' => 'boolean'
    ];

    /**
     * Get the bundle that owns the destination.
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    /**
     * Get the hotels for the destination.
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Get the active hotels for the destination.
     */
    public function activeHotels()
    {
        return $this->hasMany(Hotel::class)->where('status', 'active');
    }

    /**
     * Ensure included_items is always an array
     */
    public function getIncludedItemsAttribute($value)
    {
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a JSON string, decode it
        if (is_string($value) && json_decode($value, true)) {
            return json_decode($value, true);
        }

        // If it's not an array or JSON, return an empty array
        return [];
    }

    /**
     * Ensure gallery is always an array
     */
    public function getGalleryAttribute($value)
    {
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a JSON string, decode it
        if (is_string($value) && json_decode($value, true)) {
            return json_decode($value, true);
        }

        // If it's a comma-separated string, convert to array
        if (is_string($value) && strpos($value, ',') !== false) {
            return array_filter(array_map('trim', explode(',', $value)));
        }

        // If it's not an array or can't be converted, return an empty array
        return [];
    }
}