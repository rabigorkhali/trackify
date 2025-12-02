@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('redirections.update', $thisData->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">
                    <!-- Title Field -->
                    <div class="col-md-6">
                        <label class="form-label" for="title">{{ __('Title') }}</label> *
                        <input required value="{{ $thisData->title }}" type="text" name="title" id="title"
                               class="form-control @if ($errors->first('title')) is-invalid @endif"
                               placeholder="Title"/>
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    </div>

                    <!-- Source Link Field -->
                    <div class="col-md-6">
                        <label class="form-label" for="source_link">{{ __('Source Link') }}</label> *
                        <input required value="{{ $thisData->source_link }}" type="url" name="source_link" id="source_link"
                               class="form-control @if ($errors->first('source_link')) is-invalid @endif"
                               placeholder="Enter Source URL"/>
                        <div class="invalid-feedback">{{ $errors->first('source_link') }}</div>
                    </div>

                    <!-- Destination Link Field -->
                    <div class="col-md-6">
                        <label class="form-label" for="destination_link">{{ __('Destination Link') }}</label> *
                        <input required value="{{ $thisData->destination_link }}" type="url" name="destination_link"
                               id="destination_link"
                               class="form-control @if ($errors->first('destination_link')) is-invalid @endif"
                               placeholder="Enter Destination URL"/>
                        <div class="invalid-feedback">{{ $errors->first('destination_link') }}</div>
                    </div>

                    <!-- Redirection Type Field -->
                    <div class="col-md-6">
                        <label class="form-label" for="redirection_type">{{ __('Redirection Type') }}</label> *
                        <select name="redirection_type" id="redirection_type"
                                class="form-select @if ($errors->first('redirection_type')) is-invalid @endif">
                            @foreach (getRedirectionType() as $value => $label)
                                <option value="{{ $value }}" {{ $thisData->redirection_type == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('redirection_type') }}</div>
                    </div>

                    <!-- Status Field -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input type="radio" id="status1" name="status" value="1" {{ $thisData->status == 1 ? 'checked' : '' }}
                            class="form-check-input @if ($errors->first('status')) is-invalid @endif">
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0" {{ $thisData->status == 0 ? 'checked' : '' }}
                            class="form-check-input @if ($errors->first('status')) is-invalid @endif">
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
