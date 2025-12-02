@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('sliders.update', $thisData->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ $thisData->title }}" class="form-control @error('title') is-invalid @enderror" placeholder="Title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="sub_title">Sub Title</label>
                        <input type="text" name="sub_title" id="sub_title" value="{{ $thisData->sub_title }}" class="form-control @error('sub_title') is-invalid @enderror" placeholder="Sub Title">
                        @error('sub_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="timer">Timer (seconds)</label>
                        <input type="number" step="0.01" name="timer" id="timer" value="{{ $thisData->timer }}" class="form-control @error('timer') is-invalid @enderror" placeholder="e.g. 3.5">
                        @error('timer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="button2_label">Position</label>
                        <input type="number" name="position" id="position" value="{{ $thisData->position??old('position') }}" class="form-control @error('position') is-invalid @enderror">
                        <div class="invalid-feedback">{{ $errors->first('position') }}</div>
                    </div>
                    <!-- Button 1 -->
                    <div class="col-md-3">
                        <label class="form-label" for="button1_label">Button 1 Label</label>
                        <input type="text" name="button1_label" id="button1_label" value="{{ $thisData->button1_label }}" class="form-control @error('button1_label') is-invalid @enderror">
                        @error('button1_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_link">Button 1 Link</label>
                        <input type="text" name="button1_link" id="button1_link" value="{{ $thisData->button1_link }}" class="form-control @error('button1_link') is-invalid @enderror">
                        @error('button1_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_color">Button 1 Color</label>
                        <input type="text" name="button1_color" id="button1_color" value="{{ $thisData->button1_color }}" class="form-control @error('button1_color') is-invalid @enderror">
                        @error('button1_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button1_icon">Button 1 Icon</label>
                        <input type="text" name="button1_icon" id="button1_icon" value="{{ $thisData->button1_icon }}" class="form-control @error('button1_icon') is-invalid @enderror">
                        @error('button1_icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Button 2 -->
                    <div class="col-md-3">
                        <label class="form-label" for="button2_label">Button 2 Label</label>
                        <input type="text" name="button2_label" id="button2_label" value="{{ $thisData->button2_label }}" class="form-control @error('button2_label') is-invalid @enderror">
                        @error('button2_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_link">Button 2 Link</label>
                        <input type="text" name="button2_link" id="button2_link" value="{{ $thisData->button2_link }}" class="form-control @error('button2_link') is-invalid @enderror">
                        @error('button2_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_color">Button 2 Color</label>
                        <input type="text" name="button2_color" id="button2_color" value="{{ $thisData->button2_color }}" class="form-control @error('button2_color') is-invalid @enderror">
                        @error('button2_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="button2_icon">Button 2 Icon</label>
                        <input type="text" name="button2_icon" id="button2_icon" value="{{ $thisData->button2_icon }}" class="form-control @error('button2_icon') is-invalid @enderror">
                        @error('button2_icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="col-md-6">
                        <label class="form-label" for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror">{{ $thisData->short_description }}</textarea>
                        @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Long Description -->
                    <div class="col-md-6">
                        <label class="form-label" for="long_description">Long Description</label>
                        <textarea name="long_description" id="long_description" rows="3" class="form-control @error('long_description') is-invalid @enderror">{{ $thisData->long_description }}</textarea>
                        @error('long_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="thumbnail_image">Thumbnail Image</label>
                        <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control @error('thumbnail_image') is-invalid @enderror">
                        @if($thisData->thumbnail_image)
                            <img src="{{ asset($thisData->thumbnail_image) }}" class="img-thumbnail mt-2" style="max-width: 150px;">
                        @endif
                        @error('thumbnail_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label class="form-label w-100" for="status">Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $thisData->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ !$thisData->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_inactive">Inactive</label>
                        </div>
                        @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
