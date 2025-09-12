<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'rating',
        'star_rating',
        'price_min',
        'price_max',
        'check_in_time',
        'check_out_time',
        'main_image',
        'is_featured',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            if (empty($hotel->slug)) {
                $hotel->slug = Str::slug($hotel->name);
            }
        });

        static::updating(function ($hotel) {
            if ($hotel->isDirty('name') && !$hotel->isDirty('slug')) {
                $hotel->slug = Str::slug($hotel->name);
            }
        });
    }

    /**
     * Get the destination that the hotel belongs to.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the amenities for the hotel.
     */
    public function amenities()
    {
        return $this->belongsToMany(HotelAmenity::class, 'hotel_hotel_amenity')
            ->withPivot('details')
            ->withTimestamps();
    }

    /**
     * Get the images for the hotel.
     */
    public function images()
    {
        return $this->hasMany(HotelImage::class)->orderBy('sort_order');
    }

    /**
     * Get the featured images for the hotel.
     */
    public function featuredImages()
    {
        return $this->hasMany(HotelImage::class)->where('is_featured', true)->orderBy('sort_order');
    }

    /**
     * Get the formatted price range.
     */
    public function getPriceRangeAttribute()
    {
        if ($this->price_min && $this->price_max) {
            return '$' . number_format($this->price_min, 0) . ' - $' . number_format($this->price_max, 0);
        } elseif ($this->price_min) {
            return 'From $' . number_format($this->price_min, 0);
        } elseif ($this->price_max) {
            return 'Up to $' . number_format($this->price_max, 0);
        }
        
        return 'Price on request';
    }

    /**
     * Scope a query to only include active hotels.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured hotels.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
