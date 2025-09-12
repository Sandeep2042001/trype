@extends('layouts.app')

@section('content')
<div class="hotel-page">
    <!-- Hero Section with Image Gallery -->
    <div class="hero-section">
        <div class="hero-image-container">
            @if($hotel->images->count() > 0)
                <div class="main-image">
                    <img src="{{ $hotel->images->first()->image_path }}" alt="{{ $hotel->name }}" id="mainImage">
                    <div class="image-overlay">
                        <div class="hotel-info">
                            <h1 class="hotel-title">{{ $hotel->name }}</h1>
                            <div class="hotel-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $hotel->city }}, {{ $hotel->country }}
                            </div>
                            <div class="hotel-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $hotel->star_rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="rating-text">{{ number_format($hotel->rating, 1) }}/5</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="main-image placeholder">
                    <div class="placeholder-content">
                        <i class="fas fa-hotel fa-3x"></i>
                        <h1>{{ $hotel->name }}</h1>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Thumbnail Gallery -->
        @if($hotel->images->count() > 1)
        <div class="thumbnail-gallery">
            @foreach($hotel->images->take(6) as $index => $image)
                <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $image->image_path }}', this)">
                    <img src="{{ $image->image_path }}" alt="{{ $image->alt_text ?? $hotel->name }}">
                    @if($index === 5 && $hotel->images->count() > 6)
                        <div class="more-images">
                            <span>+{{ $hotel->images->count() - 6 }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Hotel Details -->
            <div class="col-lg-8">
                <div class="hotel-details">
                    <!-- Price Section -->
                    <div class="price-section">
                        <div class="price-main">
                            <span class="price-amount">{{ $hotel->price_range }}</span>
                            <span class="price-period">per night</span>
                        </div>
                        <div class="price-features">
                            <div class="feature">
                                <i class="fas fa-wifi"></i>
                                <span>Free WiFi</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-parking"></i>
                                <span>Free Parking</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-utensils"></i>
                                <span>Restaurant</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h3>About This Hotel</h3>
                        <div class="description-content">
                            {!! $hotel->description !!}
                        </div>
                    </div>

                    <!-- Image Gallery Section -->
                    @if($hotel->images->count() > 0)
                    <div class="gallery-section">
                        <h3>Hotel Gallery</h3>
                        <div class="gallery-grid">
                            @foreach($hotel->images as $index => $image)
                                <div class="gallery-item">
                                    <img src="{{ $image->image_path }}" alt="{{ $image->alt_text ?? $hotel->name }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Amenities -->
                    @if($hotel->amenities->count() > 0)
                    <div class="amenities-section">
                        <h3>Amenities & Services</h3>
                        <div class="amenities-grid">
                            @foreach($hotel->amenities->take(8) as $amenity)
                                <div class="amenity-item">
                                    @if($amenity->icon)
                                        <i class="{{ $amenity->icon }}"></i>
                                    @else
                                        <i class="fas fa-check"></i>
                                    @endif
                                    <span>{{ $amenity->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="booking-sidebar">
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3>Book Your Stay</h3>
                            <div class="price-display">
                                <span class="price">{{ $hotel->price_range }}</span>
                                <span class="period">per night</span>
                            </div>
                        </div>

                        <div class="booking-form">
                            <form action="{{ route('hotels.book', ['destination' => $destination, 'hotel' => $hotel]) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Check-in Date</label>
                                    <input type="date" name="check_in" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Check-out Date</label>
                                    <input type="date" name="check_out" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Guests</label>
                                    <select name="guests" class="form-control" required>
                                        <option value="1">1 Guest</option>
                                        <option value="2">2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                        <option value="5">5+ Guests</option>
                                    </select>
                                </div>

                                <div class="booking-summary">
                                    <div class="summary-item">
                                        <span>Room Rate</span>
                                        <span>{{ $hotel->price_range }}</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Taxes & Fees</span>
                                        <span>Included</span>
                                    </div>
                                    <div class="summary-total">
                                        <span>Total</span>
                                        <span>{{ $hotel->price_range }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn-purchase">
                                    <i class="fas fa-credit-card"></i>
                                    Purchase Now
                                </button>
                            </form>
                        </div>

                        <div class="booking-features">
                            <div class="feature">
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure Payment</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-clock"></i>
                                <span>Instant Confirmation</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-undo"></i>
                                <span>Free Cancellation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    /* Hotel Page Styles */
    .hotel-page {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        height: 70vh;
        overflow: hidden;
    }

    .hero-image-container {
        position: relative;
        height: 100%;
    }

    .main-image {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .main-image.placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .placeholder-content {
        text-align: center;
        color: white;
    }

    .placeholder-content i {
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 3rem 2rem 2rem;
        color: white;
    }

    .hotel-info {
        max-width: 1200px;
        margin: 0 auto;
    }

    .hotel-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .hotel-location {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .hotel-location i {
        margin-right: 0.5rem;
    }

    .hotel-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hotel-rating i {
        color: #ffd700;
        font-size: 1.2rem;
    }

    .rating-text {
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Thumbnail Gallery */
    .thumbnail-gallery {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 10;
    }

    .thumbnail {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .thumbnail.active {
        border-color: #007bff;
        transform: scale(1.1);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail:hover {
        transform: scale(1.05);
    }

    .more-images {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    /* Main Content */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .hotel-details {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    /* Price Section */
    .price-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        margin-bottom: 2rem;
    }

    .price-main {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }

    .price-amount {
        font-size: 3rem;
        font-weight: 700;
    }

    .price-period {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .price-features {
        display: flex;
        gap: 2rem;
    }

    .price-features .feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .price-features .feature i {
        font-size: 1.2rem;
    }

    /* Description Section */
    .description-section {
        margin-bottom: 2rem;
    }

    .description-section h3 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }

    .description-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #666;
    }

    /* Gallery Section */
    .gallery-section {
        margin-bottom: 2rem;
    }

    .gallery-section h3 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    /* Amenities Section */
    .amenities-section h3 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .amenity-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .amenity-item i {
        color: #007bff;
        font-size: 1.2rem;
        width: 20px;
    }

    /* Booking Sidebar */
    .booking-sidebar {
        position: sticky;
        top: 2rem;
    }

    .booking-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .booking-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .booking-header h3 {
        margin: 0 0 1rem 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .price-display {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.5rem;
    }

    .price-display .price {
        font-size: 2rem;
        font-weight: 700;
    }

    .price-display .period {
        font-size: 1rem;
        opacity: 0.9;
    }

    .booking-form {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .booking-summary {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        color: #666;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        font-size: 1.1rem;
        color: #333;
        border-top: 2px solid #dee2e6;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }

    .btn-purchase {
        width: 100%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-purchase:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40,167,69,0.3);
    }

    .booking-features {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        display: flex;
        justify-content: space-around;
        border-top: 1px solid #dee2e6;
    }

    .booking-features .feature {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.8rem;
        color: #666;
        text-align: center;
    }

    .booking-features .feature i {
        font-size: 1.2rem;
        color: #007bff;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hotel-title {
            font-size: 2rem;
        }
        
        .price-section {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .price-features {
            justify-content: center;
        }
        
        .thumbnail-gallery {
            position: static;
            transform: none;
            justify-content: center;
            margin-top: 1rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.75rem;
        }
        
        .gallery-item {
            aspect-ratio: 1;
        }
        
        .amenities-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@endsection

@push('scripts')
<script>
    // Image gallery functionality
    function changeMainImage(imageSrc, thumbnail) {
        // Update main image
        document.getElementById('mainImage').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }

    // Set minimum dates for booking form
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        const checkInInput = document.querySelector('input[name="check_in"]');
        const checkOutInput = document.querySelector('input[name="check_out"]');
        
        if (checkInInput && checkOutInput) {
            // Set minimum check-in date to today
            checkInInput.min = today.toISOString().split('T')[0];
            
            // Set minimum check-out date to tomorrow
            checkOutInput.min = tomorrow.toISOString().split('T')[0];
            
            // Update check-out minimum when check-in changes
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(checkInDate.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                // If current check-out is before new minimum, update it
                if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            });
        }
    });
</script>
@endpush 