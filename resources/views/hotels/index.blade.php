@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-3">Explore Our Hotels</h1>
            <p class="lead">Discover comfortable accommodations at the best destinations around the world.</p>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Find Your Perfect Stay</h5>
                    <form action="{{ route('hotels.search') }}" method="GET">
                        <div class="form-group">
                            <label for="destination">Destination</label>
                            <select class="form-control" id="destination" name="destination">
                                <option value="">All Destinations</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keyword">Search</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Hotel name or location">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($featuredHotels->count() > 0)
    <div class="mb-5">
        <h2 class="mb-4">Featured Hotels</h2>
        <div class="row">
            @foreach($featuredHotels as $hotel)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        @if($hotel->main_image)
                            <img src="{{ asset('storage/' . $hotel->main_image) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center py-5" style="height: 200px;">
                                <i class="fas fa-hotel fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="badge badge-primary position-absolute" style="top: 10px; right: 10px;">Featured</div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $hotel->name }}</h5>
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $hotel->city }}, {{ $hotel->country }}
                        </p>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $hotel->star_rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="card-text">{{ Str::limit(strip_tags($hotel->description), 100) }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold">{{ $hotel->price_range }}</span>
                        <a href="{{ route('hotels.show', ['destination' => $hotel->destination, 'hotel' => $hotel->slug]) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>All Hotels</h2>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort By
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown">
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'featured']) }}">Featured</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_low']) }}">Price: Low to High</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_high']) }}">Price: High to Low</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'rating']) }}">Rating</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name']) }}">Name</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.index') }}" method="GET" id="filter-form">
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'featured') }}">
                        
                        <div class="form-group">
                            <label for="filter-destination">Destination</label>
                            <select class="form-control" id="filter-destination" name="destination">
                                <option value="">All Destinations</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ request('destination') == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="filter-star">Star Rating</label>
                            <div>
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="star-{{ $i }}" name="star_rating[]" value="{{ $i }}" 
                                            {{ in_array($i, (array)request('star_rating', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="star-{{ $i }}">
                                            @for($j = 1; $j <= $i; $j++)
                                                <i class="fas fa-star text-warning small"></i>
                                            @endfor
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="price_min" placeholder="Min" value="{{ request('price_min') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="price_max" placeholder="Max" value="{{ request('price_max') }}">
                                </div>
                            </div>
                        </div>

                        @if($popularAmenities->count() > 0)
                            <div class="form-group">
                                <label>Popular Amenities</label>
                                @foreach($popularAmenities as $amenity)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="amenity-{{ $amenity->id }}" name="amenities[]" value="{{ $amenity->id }}"
                                            {{ in_array($amenity->id, (array)request('amenities', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="amenity-{{ $amenity->id }}">
                                            @if($amenity->icon)
                                                <i class="{{ $amenity->icon }} mr-1"></i>
                                            @endif
                                            {{ $amenity->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                        <a href="{{ route('hotels.index') }}" class="btn btn-outline-secondary btn-block">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                @forelse($hotels as $hotel)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($hotel->main_image)
                                <img src="{{ asset('storage/' . $hotel->main_image) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5" style="height: 180px;">
                                    <i class="fas fa-hotel fa-3x text-muted"></i>
                                </div>
                            @endif
                            @if($hotel->is_featured)
                                <div class="badge badge-primary position-absolute" style="top: 10px; right: 10px;">Featured</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $hotel->name }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt"></i> {{ $hotel->city }}, {{ $hotel->country }}
                            </p>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $hotel->star_rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            @if($hotel->amenities->count() > 0)
                                <div class="mb-2 small">
                                    @foreach($hotel->amenities->take(3) as $amenity)
                                        <span class="badge badge-light mr-1">
                                            @if($amenity->icon)
                                                <i class="{{ $amenity->icon }}"></i>
                                            @endif
                                            {{ $amenity->name }}
                                        </span>
                                    @endforeach
                                    @if($hotel->amenities->count() > 3)
                                        <span class="badge badge-light">+{{ $hotel->amenities->count() - 3 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">{{ $hotel->price_range }}</span>
                            <a href="{{ route('hotels.show', ['destination' => $hotel->destination, 'hotel' => $hotel->slug]) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <p class="mb-0">No hotels found matching your criteria. Try adjusting your filters.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit filter form when select changes
        $('#filter-destination, input[name="star_rating[]"]').on('change', function() {
            $('#filter-form').submit();
        });
    });
</script>
@endpush 