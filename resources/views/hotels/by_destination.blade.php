@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Hotels</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $destination->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-3">Hotels in {{ $destination->name }}</h1>
            <p class="lead">{{ $hotels->total() }} hotels found in this destination</p>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Find Your Perfect Stay</h5>
                    <form action="{{ route('hotels.search') }}" method="GET">
                        <input type="hidden" name="destination" value="{{ $destination->id }}">
                        <div class="form-group">
                            <label for="keyword">Search</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Hotel name or features">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($destination->main_image)
    <div class="mb-5">
        <div class="position-relative">
            <img src="{{ asset('storage/' . $destination->main_image) }}" alt="{{ $destination->name }}" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;">
            <div class="position-absolute" style="bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                <h2 class="text-white mb-0">{{ $destination->name }}</h2>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.by-destination', $destination) }}" method="GET" id="filter-form">
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'featured') }}">
                        
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
                        <a href="{{ route('hotels.by-destination', $destination) }}" class="btn btn-outline-secondary btn-block">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>All Hotels</h3>
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
                            <a href="{{ route('hotels.show', ['destination' => $destination, 'hotel' => $hotel->slug]) }}" class="btn btn-sm btn-outline-primary">View Details</a>
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

    @if($destination->description)
    <div class="mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="mb-0">About {{ $destination->name }}</h3>
            </div>
            <div class="card-body">
                {!! $destination->description !!}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit filter form when select changes
        $('input[name="star_rating[]"], input[name="amenities[]"]').on('change', function() {
            $('#filter-form').submit();
        });
    });
</script>
@endpush 