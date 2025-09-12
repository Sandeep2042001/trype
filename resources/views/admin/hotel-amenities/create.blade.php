@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Hotel Amenity</h1>
        <a href="{{ route('admin.hotel-amenities.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Amenities
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
            <h6 class="m-0 font-weight-bold text-primary">Amenity Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hotel-amenities.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Amenity Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="icon">Icon Class</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i id="icon-preview" class="fas fa-concierge-bell"></i></span>
                        </div>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', 'fas fa-concierge-bell') }}" placeholder="e.g. fas fa-wifi">
                    </div>
                    <small class="form-text text-muted">Enter a FontAwesome icon class. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a></small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Brief description of the amenity">
                </div>

                <div class="form-group">
                    <label for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-control" id="category" name="category" required>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category', 'general') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_popular" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_popular">Popular Amenity</label>
                        <small class="form-text text-muted">Popular amenities are highlighted in search filters and hotel listings.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="display_order">Display Order</label>
                    <input type="number" class="form-control" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                    <small class="form-text text-muted">Amenities with lower numbers will be displayed first.</small>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Amenity
                    </button>
                    <a href="{{ route('admin.hotel-amenities.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update icon preview when icon class changes
        $('#icon').on('input', function() {
            let iconClass = $(this).val();
            $('#icon-preview').attr('class', iconClass || 'fas fa-concierge-bell');
        });
    });
</script>
@endpush 