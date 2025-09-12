<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAmenity;
use Illuminate\Http\Request;

class HotelAmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = HotelAmenity::orderBy('category')->orderBy('name')->paginate(20);
        return view('admin.hotel-amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.hotel-amenities.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'category' => 'required|in:general,room,bathroom,dining,services,accessibility,other',
            'is_popular' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        HotelAmenity::create($validated);

        return redirect()->route('admin.hotel-amenities.index')
            ->with('success', 'Amenity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelAmenity $hotelAmenity)
    {
        return view('admin.hotel-amenities.show', compact('hotelAmenity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelAmenity $hotelAmenity)
    {
        $categories = $this->getCategories();
        return view('admin.hotel-amenities.edit', compact('hotelAmenity', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelAmenity $hotelAmenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'category' => 'required|in:general,room,bathroom,dining,services,accessibility,other',
            'is_popular' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        $hotelAmenity->update($validated);

        return redirect()->route('admin.hotel-amenities.index')
            ->with('success', 'Amenity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelAmenity $hotelAmenity)
    {
        // Check if amenity is in use
        if ($hotelAmenity->hotels()->count() > 0) {
            return redirect()->route('admin.hotel-amenities.index')
                ->with('error', 'Cannot delete amenity because it is used by one or more hotels.');
        }

        $hotelAmenity->delete();

        return redirect()->route('admin.hotel-amenities.index')
            ->with('success', 'Amenity deleted successfully.');
    }

    /**
     * Get the list of amenity categories.
     */
    private function getCategories()
    {
        return [
            'general' => 'General',
            'room' => 'Room',
            'bathroom' => 'Bathroom',
            'dining' => 'Dining',
            'services' => 'Services',
            'accessibility' => 'Accessibility',
            'other' => 'Other'
        ];
    }
}
