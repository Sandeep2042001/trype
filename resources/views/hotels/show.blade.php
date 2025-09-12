@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="position-relative mb-4">
        <div class="hotel-hero" style="height: 70vh; background: url('{{ $hotel->images->first() ? $hotel->images->first()->image_path : asset('storage/hotels/placeholder.jpg') }}') center/cover no-repeat;">
            <div class="position-absolute w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));"></div>
        </div>
        <div class="container position-absolute" style="bottom: 2rem; left: 50%; transform: translateX(-50%);">
            <div class="text-white">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}" class="text-white">Hotels</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hotels.by-destination', $destination) }}" class="text-white">{{ $destination->name }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $hotel->name }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 font-weight-bold mb-2">{{ $hotel->name }}</h1>
                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $hotel->star_rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <div>
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->country }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Image Gallery -->
        <div class="row mb-5">
            @foreach($hotel->images->take(5) as $index => $image)
                @if($index === 0)
                    <div class="col-md-8 mb-3">
                        <a href="{{ $image->image_path }}" data-fancybox="gallery" class="d-block h-100">
                            <div class="rounded overflow-hidden h-100">
                                <img src="{{ $image->image_path }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $image->alt_text ?? $hotel->name }}">
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                @else
                    <div class="col-6 mb-3">
                        <a href="{{ $image->image_path }}" data-fancybox="gallery" class="d-block">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ $image->image_path }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $image->alt_text ?? $hotel->name }}">
                                @if($index === 4 && $hotel->images->count() > 5)
                                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; background: rgba(0,0,0,0.5);">
                                        <span class="text-white h4 mb-0">+{{ $hotel->images->count() - 5 }} more</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
                        </div>
                    </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- About Section -->
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <h3 class="card-title border-bottom pb-3">About {{ $hotel->name }}</h3>
                        <div class="hotel-description">
                            {!! $hotel->description !!}
                        </div>
                    </div>
                </div>

                <!-- Amenities Section -->
                @if($hotel->amenities->count() > 0)
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <h3 class="card-title border-bottom pb-3">Amenities & Services</h3>
                        <div class="row">
                            @foreach($hotel->amenities->groupBy('category') as $category => $amenities)
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary">{{ ucfirst($category) }}</h5>
                                    <ul class="list-unstyled amenities-list">
                                        @foreach($amenities as $amenity)
                                            <li class="mb-3 d-flex align-items-center">
                                                @if($amenity->icon)
                                                    <i class="{{ $amenity->icon }} fa-lg text-primary mr-3"></i>
                                                @else
                                                    <i class="fas fa-check fa-lg text-primary mr-3"></i>
                                                @endif
                                                <div>
                                                    <span class="d-block">{{ $amenity->name }}</span>
                                                    @if($amenity->pivot->details)
                                                        <small class="text-muted">{{ $amenity->pivot->details }}</small>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Location Section -->
                @if($hotel->latitude && $hotel->longitude)
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <h3 class="card-title border-bottom pb-3">Location</h3>
                        <div id="map" class="rounded-lg" style="height: 400px;"></div>
                        <div class="mt-3">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                            {{ $hotel->address }}, {{ $hotel->city }}{{ $hotel->state ? ', ' . $hotel->state : '' }}, {{ $hotel->country }} {{ $hotel->postal_code ?? '' }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Booking Card -->
                <div class="card shadow border-0 rounded-lg mb-4 sticky-top" style="top: 2rem;">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="text-primary mb-1">{{ $hotel->price_range }}</h3>
                            <p class="text-muted mb-0">per night</p>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="font-weight-bold">Guest Rating</span>
                                <div>
                                    <span class="badge badge-primary p-2 rounded-lg">
                                        {{ number_format($hotel->rating, 1) }}/5
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">Hotel Category</span>
                                <div>
                                    @for($i = 1; $i <= $hotel->star_rating; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="font-weight-bold mb-3">Quick Info</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="far fa-clock text-primary mr-2"></i>
                                    Check-in: {{ $hotel->check_in_time }}
                                </li>
                                <li class="mb-2">
                                    <i class="far fa-clock text-primary mr-2"></i>
                                    Check-out: {{ $hotel->check_out_time }}
                                </li>
                                @if($hotel->phone)
                                    <li class="mb-2">
                                        <i class="fas fa-phone text-primary mr-2"></i>
                                        {{ $hotel->phone }}
                                    </li>
                                @endif
                                @if($hotel->email)
                                    <li class="mb-2">
                                        <i class="fas fa-envelope text-primary mr-2"></i>
                                        {{ $hotel->email }}
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <button class="btn btn-primary btn-lg btn-block mb-3" data-toggle="modal" data-target="#bookingModal">
                            Book Now
                        </button>
                        <button class="btn btn-outline-primary btn-block">
                            <i class="far fa-heart mr-2"></i>
                            Add to Wishlist
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nearby Hotels Section -->
        @if($nearbyHotels->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">Other Hotels in {{ $destination->name }}</h3>
            <div class="row">
                @foreach($nearbyHotels as $nearbyHotel)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $nearbyHotel->images->first() ? $nearbyHotel->images->first()->image_path : asset('storage/hotels/placeholder.jpg') }}" 
                                     class="card-img-top" alt="{{ $nearbyHotel->name }}" 
                                     style="height: 200px; object-fit: cover;">
                                @if($nearbyHotel->is_featured)
                                    <div class="badge badge-primary position-absolute px-3 py-2" 
                                         style="top: 1rem; right: 1rem;">Featured</div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $nearbyHotel->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $nearbyHotel->city }}, {{ $nearbyHotel->country }}
                                </p>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $nearbyHotel->star_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-primary">{{ $nearbyHotel->price_range }}</span>
                                    <a href="{{ route('hotels.show', ['destination' => $destination, 'hotel' => $nearbyHotel->slug]) }}" 
                                       class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Booking Modal -->
@section('styles')
<style>
    .hotel-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
    }
    .amenities-list li {
        transition: all 0.3s ease;
    }
    .amenities-list li:hover {
        transform: translateX(5px);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: white;
    }
    .modal-content {
        border: none;
        border-radius: 1rem;
    }
    .modal-header {
        border-bottom: none;
        padding: 2rem 2rem 1rem;
    }
    .modal-body {
        padding: 1rem 2rem;
    }
    .modal-footer {
        border-top: none;
        padding: 1rem 2rem 2rem;
    }
    .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    .date-input-group {
        position: relative;
    }
    .date-input-group i {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #4a5568;
        pointer-events: none;
    }
</style>
@endsection

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title font-weight-bold" id="bookingModalLabel">Book Your Stay at {{ $hotel->name }}</h4>
                    <p class="text-muted mb-0">Complete the form below to proceed with your booking</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info bg-light border-left border-info border-0 rounded-lg" style="border-left-width: 4px !important;">
                    <div class="d-flex">
                        <div class="mr-3">
                            <i class="fas fa-info-circle text-info fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-info mb-1">Demo Booking System</h6>
                            <p class="mb-0">This is a demonstration booking form. In a real application, this would connect to a live booking system.</p>
                        </div>
                    </div>
                </div>

                <form class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold" for="check_in">Check-in Date</label>
                                <div class="date-input-group">
                                    <input type="date" class="form-control" id="check_in" name="check_in" required>
                                    <i class="far fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold" for="check_out">Check-out Date</label>
                                <div class="date-input-group">
                                    <input type="date" class="form-control" id="check_out" name="check_out" required>
                                    <i class="far fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold" for="room_type">Room Type</label>
                                <select class="form-control" id="room_type" name="room_type">
                                    <option value="standard">Standard Room</option>
                                    <option value="deluxe">Deluxe Room</option>
                                    <option value="suite">Suite</option>
                                    <option value="family">Family Room</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold" for="adults">Adults</label>
                                <select class="form-control" id="adults" name="adults">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold" for="children">Children</label>
                                <select class="form-control" id="children" name="children">
                                    @for($i = 0; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="special_requests">Special Requests</label>
                        <textarea class="form-control" id="special_requests" name="special_requests" rows="3" 
                                  placeholder="Let us know if you have any special requirements..."></textarea>
                    </div>

                    <div class="bg-light p-4 rounded-lg mt-4">
                        <h5 class="font-weight-bold mb-3">Price Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Room Rate (per night)</span>
                            <span class="font-weight-bold">{{ $hotel->price_range }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Taxes & Fees</span>
                            <span class="font-weight-bold">Calculated at checkout</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4">
                    <i class="fas fa-lock mr-2"></i>Continue to Payment
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($hotel->latitude && $hotel->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        const hotelLocation = { lat: {{ $hotel->latitude }}, lng: {{ $hotel->longitude }} };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: hotelLocation,
        });
        const marker = new google.maps.Marker({
            position: hotelLocation,
            map: map,
            title: "{{ $hotel->name }}"
        });
    }
</script>
@endif

<script>
    $(document).ready(function() {
        // Initialize Fancybox for gallery
        if (typeof $.fancybox !== 'undefined') {
            $('[data-fancybox="gallery"]').fancybox({
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "thumbs",
                    "close"
                ]
            });
        }
        
        // Set minimum check-out date one day after check-in
        $('#check_in').on('change', function() {
            const checkInDate = new Date($(this).val());
            const nextDay = new Date(checkInDate);
            nextDay.setDate(checkInDate.getDate() + 1);
            
            const year = nextDay.getFullYear();
            const month = String(nextDay.getMonth() + 1).padStart(2, '0');
            const day = String(nextDay.getDate()).padStart(2, '0');
            
            $('#check_out').attr('min', `${year}-${month}-${day}`);
            
            if ($('#check_out').val() && new Date($('#check_out').val()) <= checkInDate) {
                $('#check_out').val(`${year}-${month}-${day}`);
            }
        });
        
        // Set today as minimum check-in date
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        $('#check_in').attr('min', `${year}-${month}-${day}`);
    });
</script>
@endpush 