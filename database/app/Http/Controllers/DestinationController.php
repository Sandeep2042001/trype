<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class DestinationController extends Controller
{    /**
     * Display a listing of destinations based on search.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $destinations = Destination::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('location', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->with('bundle')
            ->paginate(9);
            
        // Add SEO metadata
        $seoTitle = 'Search Results for "' . $search . '" - Travel Destinations';
        $seoDescription = 'Discover destinations matching "' . $search . '". Find the perfect places for your next vacation.';
        $seoKeywords = $search . ', travel destinations, vacation spots, holiday packages, ' . $search . ' travel';
        $seoImage = asset('images/default-social-share.jpg');
        $seoType = 'website';
            
        return view('destinations.search', compact('destinations', 'search', 
            'seoTitle', 'seoDescription', 'seoKeywords', 'seoImage', 'seoType'));
    }    /**
     * Display the specified destination.
     */
    public function show($id)
    {
        $destination = Destination::with('bundle')->findOrFail($id);
       
        $testimonials = Testimonial::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        // Get featured hotels for this destination
        $featuredHotels = Hotel::where('destination_id', $destination->id)
            ->where('status', 'active')
            ->where('is_featured', true)
            ->with('images')
            ->take(3)
            ->get();
            
        // Get all hotels for this destination
        $hotels = Hotel::where('destination_id', $destination->id)
            ->where('status', 'active')
            ->with('images')
            ->take(6)
            ->get();
            
        // Add SEO metadata
        $seoTitle = $destination->name . ' - ' . $destination->location;
        $seoDescription = substr(strip_tags($destination->description), 0, 160);
        $seoKeywords = $destination->name . ', ' . $destination->location . ', travel, vacation' . 
            ($destination->bundle ? ', ' . $destination->bundle->name : '') . ', holiday packages, hotels';
        $seoImage = $destination->featured_image ?? asset('images/default-social-share.jpg');
        $seoType = 'place';
        $canonicalUrl = route('destinations.show', $destination->id);
            
        return view('destinations.show', compact(
            'destination', 
            'testimonials', 
            'featuredHotels',
            'hotels',
            'seoTitle', 
            'seoDescription', 
            'seoKeywords', 
            'seoImage', 
            'seoType', 
            'canonicalUrl'
        ));
    }
}