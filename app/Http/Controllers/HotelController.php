<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of hotels.
     */
    public function index()
    {
        $hotels = Hotel::with(['destination', 'amenities'])
            ->active()
            ->orderBy('is_featured', 'desc')
            ->paginate(12);
            
        $featuredHotels = Hotel::with('destination')
            ->active()
            ->featured()
            ->take(3)
            ->get();
            
        $destinations = Destination::has('hotels')
            ->orderBy('name')
            ->get();
            
        $popularAmenities = HotelAmenity::popular()->ordered()->get();
        
        return view('hotels.index', compact('hotels', 'featuredHotels', 'destinations', 'popularAmenities'));
    }

    /**
     * Display hotels for a specific destination.
     */
    public function byDestination(Destination $destination)
    {
        $hotels = Hotel::with(['amenities'])
            ->where('destination_id', $destination->id)
            ->active()
            ->orderBy('is_featured', 'desc')
            ->paginate(12);
            
        $popularAmenities = HotelAmenity::popular()->ordered()->get();
        
        return view('hotels.by_destination', compact('hotels', 'destination', 'popularAmenities'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Destination $destination, Hotel $hotel)
    {
        // Ensure the hotel belongs to the specified destination
        if ($hotel->destination_id !== $destination->id) {
            abort(404);
        }
        
        $hotel->load(['amenities', 'images']);
        
        // Get nearby hotels in the same destination
        $nearbyHotels = Hotel::where('destination_id', $destination->id)
            ->where('id', '!=', $hotel->id)
            ->active()
            ->take(3)
            ->get();
            
        return view('hotels.show', compact('hotel', 'destination', 'nearbyHotels'));
    }

    /**
     * Search hotels.
     */
    public function search(Request $request)
    {
        $query = Hotel::with(['destination', 'amenities'])->active();
        
        // Filter by destination
        if ($request->filled('destination')) {
            $query->where('destination_id', $request->input('destination'));
        }
        
        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price_min', '>=', $request->input('price_min'));
        }
        
        if ($request->filled('price_max')) {
            $query->where('price_max', '<=', $request->input('price_max'));
        }
        
        // Filter by star rating
        if ($request->filled('star_rating')) {
            $query->where('star_rating', $request->input('star_rating'));
        }
        
        // Filter by amenities
        if ($request->filled('amenities')) {
            $amenities = $request->input('amenities');
            
            if (is_array($amenities)) {
                foreach ($amenities as $amenity) {
                    $query->whereHas('amenities', function ($q) use ($amenity) {
                        $q->where('hotel_amenity_id', $amenity);
                    });
                }
            }
        }
        
        // Search by keyword
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%");
            });
        }
        
        // Sort results
        $sortBy = $request->input('sort_by', 'featured');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price_min', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_max', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
        }
        
        $hotels = $query->paginate(12)->appends($request->except('page'));
        
        $destinations = Destination::has('hotels')->orderBy('name')->get();
        $popularAmenities = HotelAmenity::popular()->ordered()->get();
        
        return view('hotels.search', compact('hotels', 'destinations', 'popularAmenities'));
    }
}
