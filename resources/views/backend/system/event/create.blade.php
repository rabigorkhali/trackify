@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Create Event') }}</h5>

            <form class="card-body" action="{{ route('events.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label" for="title">{{ __('Title') }}</label> *
                        <input required type="text" name="title" id="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror" placeholder="Title"/>
                        <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-6">
                        <label class="form-label" for="slug">{{ __('Slug') }}</label> *
                        <input readonly required type="text" name="slug" id="slug" value="{{ old('slug') }}"
                               class="form-control @error('slug') is-invalid @enderror" placeholder="Slug"/>
                        <div class="invalid-feedback">@error('slug') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="title">{{ __('Video Url') }}</label>
                        <input required type="text" name="video_url" id="video_url" value="{{ old('video_url') }}"
                               class="form-control @error('video_url') is-invalid @enderror" placeholder="Video Url"/>
                        <div class="invalid-feedback">@error('video_url') {{ $message }} @enderror</div>
                    </div>
                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="short_description">{{ __('Description') }}</label>
                        <textarea name="short_description" id="short_description"
                                  class="text-editor form-control @error('short_description') is-invalid @enderror"
                                  placeholder="Short description">{{ old('short_description') }}</textarea>
                        <div class="invalid-feedback">@error('short_description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Long Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="long_description">{{ __('Long Description') }}</label>
                        <textarea name="long_description" id="long_description"
                                  class="form-control text-editor @error('long_description') is-invalid @enderror"
                                  placeholder="Detailed description">{{ old('long_description') }}</textarea>
                        <div class="invalid-feedback">@error('long_description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="thumbnail_image">{{ __('Thumbnail Image') }}</label>
                        <input type="file" name="thumbnail_image" id="thumbnail_image"
                               class="form-control @error('thumbnail_image') is-invalid @enderror"/>
                        <div class="invalid-feedback">@error('thumbnail_image') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="gallery">{{ __('Gallery') }}</label>
                        <input multiple type="file" name="gallery[]" id="gallery"
                               class="form-control @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror"/>
                        <div class="invalid-feedback">@error('gallery')
                            {{ $message }}
                            @enderror
                            @error('gallery.*')
                            {{ $message }}
                            @enderror</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input type="radio" id="status1" name="status" value="1" checked
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if(old('status') == '1') checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if(old('status') == '0') checked @endif>
                            <label for="status2" class="form-check-label">{{ __('Inactive') }}</label>
                        </div>
                        <div class="invalid-feedback">@error('status') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
