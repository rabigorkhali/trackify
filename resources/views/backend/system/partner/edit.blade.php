@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('partners.update', $thisData->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label> *
                        <input required value="{{ $thisData->name }}" type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Name"/>
                        <div class="invalid-feedback">@error('name') {{ $message }} @enderror</div>
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label class="form-label" for="address">{{ __('Address') }}</label>
                        <input type="text" name="address" id="address" value="{{ $thisData->address }}"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Address"/>
                        <div class="invalid-feedback">@error('address') {{ $message }} @enderror</div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Description">{{ $thisData->description }}</textarea>
                        <div class="invalid-feedback">@error('description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Contact -->
                    <div class="col-md-6">
                        <label class="form-label" for="contact">{{ __('Contact') }}</label>
                        <input type="text" name="contact" id="contact" value="{{ $thisData->contact }}"
                               class="form-control @error('contact') is-invalid @enderror"
                               placeholder="Contact Number"/>
                        <div class="invalid-feedback">@error('contact') {{ $message }} @enderror</div>
                    </div>

                    <!-- Social Media Links -->
                    @foreach(['facebook', 'instagram', 'youtube', 'linkedin', 'twitter'] as $platform)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $platform }}_url">{{ __(ucfirst($platform) . ' URL') }}</label>
                            <input type="url" name="{{ $platform }}_url" id="{{ $platform }}_url" value="{{ $thisData->{$platform.'_url'} }}"
                                   class="form-control @error($platform.'_url') is-invalid @enderror"
                                   placeholder="{{ ucfirst($platform) }} Profile URL"/>
                            <div class="invalid-feedback">@error($platform.'_url') {{ $message }} @enderror</div>
                        </div>
                    @endforeach

                    <!-- Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="image">{{ __('Image') }}</label>
                        <input type="file" name="image" id="image"
                               class="form-control @error('image') is-invalid @enderror"/>
                        <div class="invalid-feedback">@error('image') {{ $message }} @enderror</div>
                        @if ($thisData->image)
                            <div class="col-md-6 mt-2">
                                <a target="_blank" href="{{ asset($thisData->image) }}">
                                    <img src="{{ asset($thisData->image) }}" width="100" alt="Image" class="img-fluid">
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Position -->
                    <div class="col-md-6">
                        <label class="form-label" for="position">{{ __('Position') }}</label>
                        <input type="number" name="position" id="position" value="{{ $thisData->position }}"
                               class="form-control @error('position') is-invalid @enderror"
                               placeholder="Position"/>
                        <div class="invalid-feedback">@error('position') {{ $message }} @enderror</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input id="status1" type="radio" name="status" value="1"
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if($thisData->status == 1) checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if($thisData->status == 0) checked @endif>
                            <label for="status2" class="form-check-label">{{ __('Inactive') }}</label>
                        </div>
                        <div class="invalid-feedback">@error('status') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
