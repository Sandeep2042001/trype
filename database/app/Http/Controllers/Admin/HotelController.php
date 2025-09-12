<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::with('destination')->latest()->paginate(10);
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::orderBy('name')->get();
        $amenities = HotelAmenity::orderBy('name')->get();
        return view('admin.hotels.create', compact('destinations', 'amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:hotels',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'star_rating' => 'required|integer|min:1|max:5',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'check_in_time' => 'nullable|string|max:50',
            'check_out_time' => 'nullable|string|max:50',
            'main_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:hotel_amenities,id',
            'amenity_details' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'image_types' => 'nullable|array',
            'image_titles' => 'nullable|array',
            'image_alt_texts' => 'nullable|array',
        ]);

        // Handle slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('hotels/main', 'public');
            $validated['main_image'] = $mainImagePath;
        }

        // Create hotel
        $hotel = Hotel::create($validated);

        // Attach amenities with details
        if ($request->has('amenities')) {
            $amenityDetails = $request->input('amenity_details', []);
            $amenityData = [];

            foreach ($request->input('amenities') as $amenityId) {
                $amenityData[$amenityId] = [
                    'details' => $amenityDetails[$amenityId] ?? null
                ];
            }

            $hotel->amenities()->attach($amenityData);
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            $imageTypes = $request->input('image_types', []);
            $imageTitles = $request->input('image_titles', []);
            $imageAltTexts = $request->input('image_alt_texts', []);

            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('hotels/gallery', 'public');
                
                HotelImage::create([
                    'hotel_id' => $hotel->id,
                    'image_path' => $imagePath,
                    'type' => $imageTypes[$index] ?? 'other',
                    'title' => $imageTitles[$index] ?? null,
                    'alt_text' => $imageAltTexts[$index] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load(['destination', 'amenities', 'images']);
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        $hotel->load(['amenities', 'images']);
        $destinations = Destination::orderBy('name')->get();
        $amenities = HotelAmenity::orderBy('name')->get();
        
        return view('admin.hotels.edit', compact('hotel', 'destinations', 'amenities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:hotels,slug,' . $hotel->id,
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'star_rating' => 'required|integer|min:1|max:5',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'check_in_time' => 'nullable|string|max:50',
            'check_out_time' => 'nullable|string|max:50',
            'main_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:hotel_amenities,id',
            'amenity_details' => 'nullable|array',
            'remove_main_image' => 'nullable|boolean',
        ]);

        // Handle slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle main image upload or removal
        if ($request->hasFile('main_image')) {
            // Delete old image if exists
            if ($hotel->main_image && Storage::disk('public')->exists($hotel->main_image)) {
                Storage::disk('public')->delete($hotel->main_image);
            }
            
            $mainImagePath = $request->file('main_image')->store('hotels/main', 'public');
            $validated['main_image'] = $mainImagePath;
        } elseif ($request->boolean('remove_main_image')) {
            // Delete old image if exists
            if ($hotel->main_image && Storage::disk('public')->exists($hotel->main_image)) {
                Storage::disk('public')->delete($hotel->main_image);
            }
            
            $validated['main_image'] = null;
        }

        // Update hotel
        $hotel->update($validated);

        // Sync amenities with details
        if ($request->has('amenities')) {
            $amenityDetails = $request->input('amenity_details', []);
            $amenityData = [];

            foreach ($request->input('amenities') as $amenityId) {
                $amenityData[$amenityId] = [
                    'details' => $amenityDetails[$amenityId] ?? null
                ];
            }

            $hotel->amenities()->sync($amenityData);
        } else {
            $hotel->amenities()->detach();
        }

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        // Delete main image if exists
        if ($hotel->main_image && Storage::disk('public')->exists($hotel->main_image)) {
            Storage::disk('public')->delete($hotel->main_image);
        }

        // Delete all related images
        foreach ($hotel->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }

    /**
     * Upload hotel gallery images.
     */
    public function uploadImages(Request $request, Hotel $hotel)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048',
            'image_types' => 'nullable|array',
            'image_titles' => 'nullable|array',
            'image_alt_texts' => 'nullable|array',
        ]);

        $imageTypes = $request->input('image_types', []);
        $imageTitles = $request->input('image_titles', []);
        $imageAltTexts = $request->input('image_alt_texts', []);
        
        // Get the current max sort order
        $maxSortOrder = $hotel->images()->max('sort_order') ?? -1;

        foreach ($request->file('images') as $index => $image) {
            $imagePath = $image->store('hotels/gallery', 'public');
            
            HotelImage::create([
                'hotel_id' => $hotel->id,
                'image_path' => $imagePath,
                'type' => $imageTypes[$index] ?? 'other',
                'title' => $imageTitles[$index] ?? null,
                'alt_text' => $imageAltTexts[$index] ?? null,
                'sort_order' => $maxSortOrder + $index + 1,
            ]);
        }

        return redirect()->route('admin.hotels.edit', $hotel)
            ->with('success', 'Images uploaded successfully.');
    }

    /**
     * Delete a hotel image.
     */
    public function deleteImage(HotelImage $image)
    {
        $hotelId = $image->hotel_id;

        // Delete the image file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()->route('admin.hotels.edit', $hotelId)
            ->with('success', 'Image deleted successfully.');
    }

    /**
     * Update image details.
     */
    public function updateImage(Request $request, HotelImage $image)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'type' => 'required|in:room,exterior,interior,amenity,other',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $image->update($validated);

        return redirect()->route('admin.hotels.edit', $image->hotel_id)
            ->with('success', 'Image updated successfully.');
    }
}
