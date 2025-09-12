@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $hotel->name }}</h1>
        <div>
            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-primary btn-sm shadow-sm mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Hotel
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Hotels
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hotel Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $hotel->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $hotel->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $hotel->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Destination</th>
                                    <td>{{ $hotel->destination->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $hotel->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($hotel->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Featured</th>
                                    <td>
                                        @if($hotel->is_featured)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Star Rating</th>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $hotel->star_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </td>
                                </tr>
                                <tr>
                                    <th>User Rating</th>
                                    <td>{{ $hotel->rating }} / 5</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Price Range</th>
                                    <td>{{ $hotel->price_range }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $hotel->address }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $hotel->city }}</td>
                                </tr>
                                <tr>
                                    <th>State</th>
                                    <td>{{ $hotel->state ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $hotel->country }}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code</th>
                                    <td>{{ $hotel->postal_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Check-in Time</th>
                                    <td>{{ $hotel->check_in_time }}</td>
                                </tr>
                                <tr>
                                    <th>Check-out Time</th>
                                    <td>{{ $hotel->check_out_time }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Contact Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">Phone</th>
                                    <td>{{ $hotel->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $hotel->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>
                                        @if($hotel->website)
                                            <a href="{{ $hotel->website }}" target="_blank">{{ $hotel->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! $hotel->description !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($hotel->amenities->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Amenities</h5>
                                <div class="row">
                                    @foreach($hotel->amenities->groupBy('category') as $category => $amenities)
                                        <div class="col-md-4 mb-4">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">{{ ucfirst($category) }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush">
                                                        @foreach($amenities as $amenity)
                                                            <li class="list-group-item">
                                                                @if($amenity->icon)
                                                                    <i class="{{ $amenity->icon }}"></i>
                                                                @endif
                                                                {{ $amenity->name }}
                                                                @if($amenity->pivot->details)
                                                                    <small class="d-block text-muted">{{ $amenity->pivot->details }}</small>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($hotel->latitude && $hotel->longitude)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Location</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p>Latitude: {{ $hotel->latitude }}, Longitude: {{ $hotel->longitude }}</p>
                                        <div id="map" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Main Image</h6>
                </div>
                <div class="card-body">
                    @if($hotel->main_image)
                        <img src="{{ asset('storage/' . $hotel->main_image) }}" alt="{{ $hotel->name }}" class="img-fluid">
                    @else
                        <div class="alert alert-info mb-0">
                            No main image uploaded.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gallery Images</h6>
                </div>
                <div class="card-body">
                    @if($hotel->images->count() > 0)
                        <div class="row">
                            @foreach($hotel->images as $image)
                                <div class="col-md-6 mb-3">
                                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?? $hotel->name }}" class="img-thumbnail">
                                    </a>
                                    <div class="small mt-1">
                                        {{ ucfirst($image->type) }}
                                        @if($image->is_featured)
                                            <span class="badge badge-success">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No gallery images uploaded.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Hotel
                    </a>
                    <a href="{{ route('hotels.show', ['destination' => $hotel->destination, 'hotel' => $hotel->slug]) }}" class="btn btn-info btn-block mb-2" target="_blank">
                        <i class="fas fa-eye"></i> View on Website
                    </a>
                    <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this hotel?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Delete Hotel
                        </button>
                    </form>
                </div>
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
@endpush 