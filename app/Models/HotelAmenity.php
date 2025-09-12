<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'category',
        'is_popular',
        'display_order',
    ];

    /**
     * Get the hotels that have this amenity.
     */
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_hotel_amenity')
            ->withPivot('details')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include popular amenities.
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope a query to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
