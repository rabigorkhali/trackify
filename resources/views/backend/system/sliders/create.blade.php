@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Title">
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    </div>

                    <!-- Sub Title -->
                    <div class="col-md-6">
                        <label class="form-label" for="sub_title">Sub Title</label>
                        <input type="text" name="sub_title" id="sub_title" value="{{ old('sub_title') }}" class="form-control @error('sub_title') is-invalid @enderror" placeholder="Sub Title">
                        <div class="invalid-feedback">{{ $errors->first('sub_title') }}</div>
                    </div>



                    <!-- Timer -->
                    <div class="col-md-6">
                        <label class="form-label" for="timer">Timer (in seconds)</label>
                        <input type="number" step="0.1" name="timer" id="timer" value="{{ old('timer') }}" class="form-control @error('timer') is-invalid @enderror" placeholder="e.g., 5.0">
                        <div class="invalid-feedback">{{ $errors->first('timer') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="button2_label">Position</label>
                        <input type="number" name="position" id="position" value="{{ old('position') }}" class="form-control @error('position') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('position') }}</div>
                    </div>
                    <!-- Button 1 -->
                    <div class="col-md-3">
                        <label class="form-label" for="button1_label">Button 1 Label</label>
                        <input type="text" name="button1_label" id="button1_label" value="{{ old('button1_label') }}" class="form-control @error('button1_label') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button1_label') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_link">Button 1 Link</label>
                        <input type="text" name="button1_link" id="button1_link" value="{{ old('button1_link') }}" class="form-control @error('button1_link') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button1_link') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_color">Button 1 Color</label>
                        <input type="text" name="button1_color" id="button1_color" value="{{ old('button1_color') }}" class="form-control @error('button1_color') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button1_color') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_icon">Button 1 Icon</label>
                        <input type="text" name="button1_icon" id="button1_icon" value="{{ old('button1_icon') }}" class="form-control @error('button1_icon') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button1_icon') }}</div>
                    </div>

                    <!-- Button 2 -->
                    <div class="col-md-3">
                        <label class="form-label" for="button2_label">Button 2 Label</label>
                        <input type="text" name="button2_label" id="button2_label" value="{{ old('button2_label') }}" class="form-control @error('button2_label') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button2_label') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_link">Button 2 Link</label>
                        <input type="text" name="button2_link" id="button2_link" value="{{ old('button2_link') }}" class="form-control @error('button2_link') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button2_link') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_color">Button 2 Color</label>
                        <input type="text" name="button2_color" id="button2_color" value="{{ old('button2_color') }}" class="form-control @error('button2_color') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button2_color') }}</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_icon">Button 2 Icon</label>
                        <input type="text" name="button2_icon" id="button2_icon" value="{{ old('button2_icon') }}" class="form-control @error('button2_icon') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('button2_icon') }}</div>
                    </div>


                    <!-- Short Description -->
                    <div class="col-md-6">
                        <label class="form-label" for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror" placeholder="Short Description">{{ old('short_description') }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('short_description') }}</div>
                    </div>

                    <!-- Long Description -->
                    <div class="col-md-6">
                        <label class="form-label" for="long_description">Long Description</label>
                        <textarea name="long_description" id="long_description" rows="3" class="form-control @error('long_description') is-invalid @enderror" placeholder="Long Description">{{ old('long_description') }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('long_description') }}</div>
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="thumbnail_image">Thumbnail Image</label>
                        <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control @error('thumbnail_image') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('thumbnail_image') }}</div>
                    </div>
                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" id="status_active" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" id="status_inactive" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_inactive">Inactive</label>
                        </div>
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    </div>

                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
