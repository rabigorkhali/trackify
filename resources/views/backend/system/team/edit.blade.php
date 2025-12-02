@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('teams.update', $thisData->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label> *
                        <input required value="{{ $thisData->name }}" type="text" name="name" id="name"
                               class="form-control @if ($errors->first('name')) is-invalid @endif"
                               placeholder="Name"/>
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    </div>

                    <!-- Contact -->
                    <div class="col-md-6">
                        <label class="form-label" for="contact">{{ __('Contact') }}</label>
                        <input value="{{ $thisData->contact }}" type="text" name="contact" id="contact"
                               class="form-control @if ($errors->first('contact')) is-invalid @endif"
                               placeholder="Contact"/>
                        <div class="invalid-feedback">{{ $errors->first('contact') }}</div>
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6">
                        <label class="form-label" for="designation">{{ __('Designation') }}</label>
                        <input value="{{ $thisData->designation }}" type="text" name="designation" id="designation"
                               class="form-control @if ($errors->first('designation')) is-invalid @endif"
                               placeholder="Designation"/>
                        <div class="invalid-feedback">{{ $errors->first('designation') }}</div>
                    </div>

                    <!-- Social Media URLs -->
                    @foreach(['facebook', 'instagram', 'youtube', 'linkedin', 'twitter'] as $social)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $social }}_url">{{ ucfirst($social) }} {{ __('URL') }}</label>
                            <input type="url" name="{{ $social }}_url" id="{{ $social }}_url" 
                                   value="{{ $thisData->{$social . '_url'} }}"
                                   class="form-control @if ($errors->first($social.'_url')) is-invalid @endif"
                                   placeholder="{{ ucfirst($social) }} URL"/>
                            <div class="invalid-feedback">{{ $errors->first($social.'_url') }}</div>
                        </div>
                    @endforeach

                    <!-- Join Date -->
                    {{-- <div class="col-md-6">
                        <label class="form-label" for="join_date">{{ __('Join Date') }}</label>
                        <input type="date" name="join_date" id="join_date" value="{{ $thisData->join_date }}"
                               class="form-control @if ($errors->first('join_date')) is-invalid @endif"/>
                        <div class="invalid-feedback">{{ $errors->first('join_date') }}</div>
                    </div> --}}

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @if ($errors->first('description')) is-invalid @endif"
                                  placeholder="Description">{{ $thisData->description }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                    </div>

                    <!-- Image -->
                    <div class="col-md-6">
                        <label class="form-label" for="image">{{ __('Image') }}</label>
                        <input type="file" name="image" id="image"
                               class="form-control @if ($errors->first('image')) is-invalid @endif"/>
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                        @if ($thisData->image)
                            <div class="mt-2">
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
                               class="form-control @if ($errors->first('position')) is-invalid @endif"
                               placeholder="Position"/>
                        <div class="invalid-feedback">{{ $errors->first('position') }}</div>
                    </div>

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
