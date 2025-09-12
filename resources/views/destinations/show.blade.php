@extends('layouts.app')

@section('title', $destination->name . ' - ' . $destination->location)

@section('content')
<!-- Destination Hero -->
<section class="relative pt-24 pb-24 text-white">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ filter_var($destination->main_image, FILTER_VALIDATE_URL) ? $destination->main_image : asset($destination->main_image) }}');">
        @if(count($destination->gallery) > 0)
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ filter_var($destination->gallery[0], FILTER_VALIDATE_URL) ? $destination->gallery[0] : asset($destination->gallery[0]) }}');"></div>
        @endif
        <!-- Dynamic overlay using CSS variables -->
        <div class="absolute inset-0 bg-gradient-to-t from-teal-500/90 to-primary-600/30 " style="opacity: 0.6"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-2 drop-shadow-lg">{{ $destination->name }}</h1>
            <p class="text-xl opacity-90 mb-6 drop-shadow-md">{{ $destination->location }}</p>
            
            <div class="inline-block px-6 py-2 bg-yellow-500 rounded-full text-white font-semibold shadow-lg">
                @if($destination->bundle)
                    Part of {{ $destination->bundle->name }} Bundle
                @else
                    Featured Destination
                @endif
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0 w-full" style="z-index: 1;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" style="width: 100%; height: 120px; display: block;">
            <path fill="#ffffff" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Left Column - Main Content -->
            <div class="lg:w-2/3">
                <!-- Main Image -->
                <div class="mb-8 rounded-2xl overflow-hidden shadow-lg h-96">
                    <img 
                        src="{{ filter_var($destination->main_image, FILTER_VALIDATE_URL) ? $destination->main_image : asset($destination->main_image) }}" 
                        alt="{{ $destination->name }}"
                        class="w-full h-full object-cover"
                    >
                </div>
                
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">About {{ $destination->name }}</h2>
                    <div class="prose max-w-none text-gray-600">
                        {{ $destination->description }}
                    </div>
                </div>
                
                <!-- Gallery -->
                @if(count($destination->gallery) > 0)
                <div class="bg-white rounded-2xl shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gallery</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($destination->gallery as $image)
                            <div class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden">
                                <img 
                                    src="{{ filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image) }}" 
                                    alt="{{ $destination->name }} gallery image"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Restrictions -->
                @if($destination->restrictions)
                <div class="bg-white rounded-2xl shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Restrictions & Important Information</h2>
                    <div class="prose max-w-none text-gray-600">
                        {{ $destination->restrictions }}
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column - Sidebar -->
            <div class="lg:w-1/3">
                <!-- What's Included -->
                <div class="bg-white rounded-2xl shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">What's Included</h2>
                    <ul class="space-y-4">
                        @foreach($destination->included_items as $item)
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Bundle Information (if part of a bundle) -->
                @if($destination->bundle)                <div class="bg-gradient-to-r from-primary-50 to-indigo-50 rounded-2xl shadow-md p-8 mb-8 relative overflow-hidden bundle-shine">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold text-primary-800 mb-2">Part of a Bundle</h2>
                        <p class="text-gray-700 mb-4">This destination is part of our {{ $destination->bundle->name }} package.</p>
                        
                        <div class="bg-white p-4 rounded-lg mb-4 shadow-sm">
                            <div class="text-sm text-gray-500">Starting at</div>
                            <div class="text-3xl font-bold text-primary-700">${{ $destination->bundle->price }}</div>
                            <div class="text-sm text-gray-500">per person</div>
                        </div>
                          <a href="{{ route('bundles.show', $destination->bundle->slug) }}" class="block w-full text-center py-3 bg-gradient-to-r from-teal-500 to-indigo-700 text-white rounded-full font-semibold hover:from-primary-700 hover:to-indigo-800 transition-colors shadow-lg">
                            VIEW BUNDLE DETAILS
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Inquire About This Destination</h2>
                    <form action="#" method="POST">
                        @csrf
                        <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                          <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                          <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-600 to-indigo-700 text-white rounded-full font-semibold hover:from-primary-700 hover:to-indigo-800 transition-colors shadow-lg">
                            SEND INQUIRY
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hotels Section -->
@if($hotels->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-primary-800 mb-12 text-center">
            <span class="relative inline-block">
                HOTELS IN {{ strtoupper($destination->name) }}
                <span class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400"></span>
            </span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($hotels as $hotel)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative h-64">
                        @if($hotel->images->where('is_featured', true)->first())
                            <img 
                                src="{{ $hotel->images->where('is_featured', true)->first()->image_path }}" 
                                alt="{{ $hotel->name }}"
                                class="w-full h-full object-cover"
                            >
                        @elseif($hotel->images->first())
                            <img 
                                src="{{ $hotel->images->first()->image_path }}" 
                                alt="{{ $hotel->name }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="bg-gray-200 w-full h-full flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif
                        
                        @if($hotel->is_featured)
                            <div class="absolute top-0 right-0 bg-yellow-500 text-white px-4 py-1 rounded-bl-lg font-medium">
                                Featured
                            </div>
                        @endif
                        
                        <div class="absolute bottom-0 left-0 w-full">
                            <div class="bg-gradient-to-t from-black to-transparent text-white p-4">
                                <div class="font-semibold text-lg">{{ $hotel->name }}</div>
                                <div class="flex items-center text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $hotel->star_rating)
                                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center text-gray-600 mb-4">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $hotel->address }}, {{ $hotel->city }}</span>
                        </div>
                        
                        @if($hotel->amenities->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($hotel->amenities->take(3) as $amenity)
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                        @if($amenity->icon)
                                            <i class="{{ $amenity->icon }} mr-1"></i>
                                        @endif
                                        {{ $amenity->name }}
                                    </span>
                                @endforeach
                                @if($hotel->amenities->count() > 3)
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                        +{{ $hotel->amenities->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center mt-6">
                            <div>
                                <div class="text-sm text-gray-500">Starting from</div>
                                <div class="text-xl font-bold text-primary-700">{{ $hotel->price_range }}</div>
                            </div>
                            <a href="{{ route('hotels.show', ['destination' => $destination, 'hotel' => $hotel->slug]) }}" class="px-4 py-2 bg-gradient-to-r from-teal-500 to-indigo-700 text-white rounded-full font-medium hover:from-primary-700 hover:to-indigo-800 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($hotels->count() >= 6)
            <div class="text-center mt-12">
                <a href="{{ route('hotels.by-destination', $destination) }}" class="inline-block px-8 py-3 bg-white border-2 border-primary-600 text-primary-600 rounded-full font-semibold hover:bg-primary-50 transition-colors shadow-sm">
                    VIEW ALL HOTELS IN {{ strtoupper($destination->name) }}
                </a>
            </div>
        @endif
    </div>
</section>
@endif

<x-testimonials :testimonials="$testimonials" />


<!-- Related Destinations (if applicable) -->
@if($destination->bundle && $destination->bundle->destinations->count() > 1)
<section class="py-16">
    <div class="container mx-auto px-4">        <h2 class="text-3xl font-bold text-primary-800 mb-12 text-center">
            <span class="relative inline-block">
                OTHER DESTINATIONS IN THIS BUNDLE
                <span class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400"></span>
            </span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destination->bundle->destinations as $relatedDestination)
                @if($relatedDestination->id != $destination->id)
                    <!-- Destination Card -->
                    <div class="destination-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="relative h-64">
                            <img 
                                src="{{ filter_var($relatedDestination->main_image, FILTER_VALIDATE_URL) ? $relatedDestination->main_image : asset($relatedDestination->main_image) }}" 
                                alt="{{ $relatedDestination->name }}"
                                class="w-full h-full object-cover"
                            >                            <div class="absolute top-0 left-0 page-title-bg text-white px-4 py-2 rounded-br-lg font-medium">
                                {{ $relatedDestination->name }}, {{ $relatedDestination->location }}
                            </div>
                            <div class="absolute bottom-0 left-0 w-full">
                                <div class="bg-gradient-to-t from-black to-transparent text-white p-4">
                                    <div class="font-semibold text-lg">{{ $relatedDestination->name }}</div>
                                    <div class="text-sm">{{ $relatedDestination->location }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-6 line-clamp-3">{{ \Illuminate\Support\Str::limit($relatedDestination->description, 100) }}</p>
                              <a href="{{ route('destinations.show', $relatedDestination->id) }}" class="block w-full text-center py-3 bg-gradient-to-r from-primary-600 to-indigo-700 text-white rounded-full font-semibold hover:from-primary-700 hover:to-indigo-800 transition-colors shadow-lg">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection