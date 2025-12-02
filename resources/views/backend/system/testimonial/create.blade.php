@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Create Testimonial') }}</h5>

            <form class="card-body" action="{{ route('testimonials.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label> *
                        <input required type="text" name="name" id="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" placeholder="Name" />
                        <div class="invalid-feedback">@error('name') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Designation') }}</label> *
                        <input required type="text" name="designation" id="designation" value="{{ old('designation') }}"
                               class="form-control @error('designation') is-invalid @enderror" placeholder="Designation" />
                        <div class="invalid-feedback">@error('designation') {{ $message }} @enderror</div>
                    </div>

{{--                    <!-- Date -->--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label" for="date">{{ __('Date') }}</label> *--}}
{{--                        <input required type="date" name="date" id="date" value="{{ old('date') }}"--}}
{{--                               class="form-control @error('date') is-invalid @enderror" placeholder="Date" />--}}
{{--                        <div class="invalid-feedback">@error('date') {{ $message }} @enderror</div>--}}
{{--                    </div>--}}

                    <!-- Message -->
                    <div class="col-md-12">
                        <label class="form-label" for="message">{{ __('Message') }}</label> *
                        <textarea required name="message" id="message"
                                  class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Message">{{ old('message') }}</textarea>
                        <div class="invalid-feedback">@error('message') {{ $message }} @enderror</div>
                    </div>

                    <!-- Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="image">{{ __('Image') }}</label>
                        <input type="file" name="image" id="image"
                               class="form-control @error('image') is-invalid @enderror" />
                        <div class="invalid-feedback">@error('image') {{ $message }} @enderror</div>
                    </div>

                    <!-- Position -->
                    <div class="col-md-6">
                        <label class="form-label" for="position">{{ __('Position') }}</label>
                        <input type="number" name="position" id="position" value="{{ old('position') }}"
                               class="form-control @error('position') is-invalid @enderror" placeholder="Position" />
                        <div class="invalid-feedback">@error('position') {{ $message }} @enderror</div>
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
