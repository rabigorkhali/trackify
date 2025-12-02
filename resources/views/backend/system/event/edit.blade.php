@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('events.update', $thisData->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label" for="title">{{ __('Title') }}</label> *
                        <input required value="{{ $thisData->title }}" type="text" name="title" id="title"
                               class="form-control @if ($errors->first('title')) is-invalid @endif"
                               placeholder="Title"/>
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-6">
                        <label class="form-label" for="slug">{{ __('Slug') }}</label> *
                        <input required value="{{ $thisData->slug }}" type="text" name="slug" id="slug"
                               class="form-control @if ($errors->first('slug')) is-invalid @endif"
                               placeholder="Slug"/>
                        <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="title">{{ __('Video Url') }}</label>
                        <input required type="text" name="video_url" id="video_url" value="{{ $thisData->video_url??old('video_url') }}"
                               class="form-control @error('video_url') is-invalid @enderror" placeholder="Video Url"/>
                        <div class="invalid-feedback">@error('video_url') {{ $message }} @enderror</div>
                    </div>
                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="short_description">{{ __('Description') }}</label>
                        <textarea name="short_description" id="short_description"
                                  class="text-editor form-control @error('short_description') is-invalid @enderror"
                                  placeholder="Short description">{{ $thisData->short_description??old('short_description') }}</textarea>
                        <div class="invalid-feedback">@error('short_description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Long Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="long_description">{{ __('Long Description') }}</label>
                        <textarea name="long_description" id="long_description" rows="4"
                                  class="form-control text-editor @if ($errors->first('long_description')) is-invalid @endif"
                                  placeholder="Detailed description">{{ $thisData->long_description }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('long_description') }}</div>
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="thumbnail_image">{{ __('Thumbnail Image') }}</label>
                        <input type="file" name="thumbnail_image" id="thumbnail_image"
                               class="form-control @if ($errors->first('thumbnail_image')) is-invalid @endif"/>
                        <div class="invalid-feedback">{{ $errors->first('thumbnail_image') }}</div>
                        @if ($thisData->thumbnail_image)
                            <div class="col-md-6 mt-2">
                                <a target="_blank" href="{{ asset($thisData->thumbnail_image) }}">
                                    <img src="{{ asset($thisData->thumbnail_image) }}" width="100" alt="Thumbnail"
                                         class="img-fluid">
                                </a>
                            </div>
                        @endif
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
                    <hr>
                    <h5>Images</h5>
                    <div class="row g-2">
                        @if($thisData->galleries && $thisData->galleries->count())
                            @foreach($thisData->galleries as $galleryDatum)
                                <div class="col-1"><a href="{{ asset($galleryDatum->image) }}" target="_blank">
                                        <img src="{{ asset($galleryDatum->image) }}" class="img-fluid rounded"
                                             style="width: 100px; height: auto;" alt="Gallery Image">
                                        <a onclick="return confirmDelete(event, this);" href="{{route('deleteGallery',$galleryDatum->id)}}
                                        "
                                        class="btn btn-sm btn-danger d-flex align-items-center mt-1
                                        justify-content-center"
                                        style="width: 24px; height: 24px; padding: 0; border-radius: 50%;">
                                        <i class="fas fa-times"></i>
                                    </a>

                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p>No images found.</p>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input id="status1" type="radio" name="status" value="1"
                                   class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                   @if($thisData->status == 1) checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                   class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                   @if($thisData->status == 0) checked @endif>
                            <label for="status2" class="form-check-label">{{ __('Inactive') }}</label>
                        </div>
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete(event, element) {
            event.preventDefault(); // Prevent the default link action
            const userConfirmed = confirm("Are you sure you want to delete this image?");
            if (userConfirmed) {
                // Redirect to the delete URL
                window.location.href = element.href;
            }
        }
    </script>
@endsection()
