@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Hotel</h1>
        <a href="{{ route('admin.hotels.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Hotels
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hotel Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug">Slug (URL-friendly name)</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate">
                            <small class="form-text text-muted">If left blank, this will be generated automatically.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="destination_id">Destination <span class="text-danger">*</span></label>
                    <select class="form-control" id="destination_id" name="destination_id" required>
                        <option value="">Select Destination</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" class="form-control" id="website" name="website" value="{{ old('website') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="star_rating">Star Rating <span class="text-danger">*</span></label>
                            <select class="form-control" id="star_rating" name="star_rating" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('star_rating', 3) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rating">User Rating (0-5)</label>
                            <input type="number" step="0.1" min="0" max="5" class="form-control" id="rating" name="rating" value="{{ old('rating', 0) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_min">Minimum Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control" id="price_min" name="price_min" value="{{ old('price_min') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_max">Maximum Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control" id="price_max" name="price_max" value="{{ old('price_max') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_in_time">Check-in Time</label>
                            <input type="text" class="form-control" id="check_in_time" name="check_in_time" value="{{ old('check_in_time', '14:00') }}" placeholder="e.g. 14:00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_out_time">Check-out Time</label>
                            <input type="text" class="form-control" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', '11:00') }}" placeholder="e.g. 11:00">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="main_image">Main Image</label>
                    <input type="file" class="form-control-file" id="main_image" name="main_image">
                </div>

                <div class="form-group">
                    <label>Additional Images</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
                        <label class="custom-file-label" for="images">Choose files</label>
                    </div>
                    <small class="form-text text-muted">You can select multiple files at once.</small>
                </div>

                <div id="image-details-container" class="d-none">
                    <h6 class="mt-4">Image Details</h6>
                    <div id="image-details"></div>
                </div>

                <div class="form-group mt-4">
                    <label>Amenities</label>
                    <div class="row">
                        @foreach($amenities as $amenity)
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="amenity_{{ $amenity->id }}" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="amenity_{{ $amenity->id }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                                <div class="amenity-details d-none ml-4 mt-1">
                                    <input type="text" class="form-control form-control-sm" name="amenity_details[{{ $amenity->id }}]" placeholder="Additional details (optional)" value="{{ old('amenity_details.' . $amenity->id) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">Featured Hotel</label>
                                <small class="form-text text-muted">Featured hotels appear prominently on the website.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Hotel
                    </button>
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize WYSIWYG editor for description
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('description');
        }
        
        // Generate slug from name
        $('#name').on('blur', function() {
            if ($('#slug').val() === '') {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^a-z0-9-]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                $('#slug').val(slug);
            }
        });
        
        // Show details field when amenity is checked
        $('input[name="amenities[]"]').on('change', function() {
            let detailsField = $(this).closest('.custom-checkbox').next('.amenity-details');
            if ($(this).is(':checked')) {
                detailsField.removeClass('d-none');
            } else {
                detailsField.addClass('d-none');
            }
        });
        
        // Initialize amenity details visibility
        $('input[name="amenities[]"]:checked').each(function() {
            $(this).closest('.custom-checkbox').next('.amenity-details').removeClass('d-none');
        });
        
        // Handle multiple file selection
        $('#images').on('change', function() {
            let fileCount = this.files.length;
            $('.custom-file-label').text(fileCount + ' files selected');
            
            if (fileCount > 0) {
                $('#image-details-container').removeClass('d-none');
                let detailsHtml = '';
                
                for (let i = 0; i < fileCount; i++) {
                    detailsHtml += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Image ${i + 1}: ${this.files[i].name}</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control" name="image_types[${i}]">
                                            <option value="room">Room</option>
                                            <option value="exterior">Exterior</option>
                                            <option value="interior">Interior</option>
                                            <option value="amenity">Amenity</option>
                                            <option value="other" selected>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="image_titles[${i}]" placeholder="Image title">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Alt Text</label>
                                        <input type="text" class="form-control" name="image_alt_texts[${i}]" placeholder="Alternative text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }
                
                $('#image-details').html(detailsHtml);
            } else {
                $('#image-details-container').addClass('d-none');
                $('#image-details').html('');
            }
        });
    });
</script>
@endpush 