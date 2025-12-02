@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')

        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('emails.update', $thisData->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">

                <div class="row g-3">
                    <!-- From Email -->
                    <div class="col-md-12">
                        <label class="form-label" for="from_email">{{ __('From Email') }}</label> *
                        <input readonly required value="{{ env('MAIL_FROM_ADDRESS')?? $thisData->from_email }}" type="email"
                               name="from_email" id="from_email"
                               class="form-control @error('from_email') is-invalid @enderror"
                               placeholder="From email address">
                        <div class="invalid-feedback">{{ $errors->first('from_email') }}</div>
                    </div>

                    <!-- To Email (Multi-select with custom input) -->
                    <div class="col-md-12">
                        <label class="form-label" for="to_email">{{ __('To Email') }}</label> *
                        <button type="button" id="select-all" class="btn btn-sm btn-primary m-2">{{ __('Select All') }}</button>
                        <select name="to_email[]" id="to_email"
                                class="form-control select2-multiple @error('to_email') is-invalid @enderror" multiple>
                            @php
                                $selectedEmails = old('to_email',  $thisData->to_email);
                            @endphp
                            @foreach(getAllEmails() as $getAllEmails)
                                <option value="{{ $getAllEmails }}" {{ collect($selectedEmails)->contains($getAllEmails) ? 'selected' : '' }}>
                                    {{ $getAllEmails }}
                                </option>
                            @endforeach
                        </select>
                        @foreach ($errors->get('to_email.*') as $index => $messages)
                            @foreach ($messages as $message)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @endforeach
                        @endforeach                    </div>

                    <!-- Subject -->
                    <div class="col-md-12">
                        <label class="form-label" for="subject">{{ __('Subject') }}</label> *
                        <input required value="{{ old('subject', $thisData->subject) }}" type="text" name="subject"
                               id="subject"
                               class="form-control @error('subject') is-invalid @enderror"
                               placeholder="Email subject">
                        <div class="invalid-feedback">{{ $errors->first('subject') }}</div>
                    </div>

                    <!-- Body -->
                    <div class="col-md-12">
                        <label class="form-label" for="body">{{ __('Body') }}</label> *
                        <textarea required name="body" id="body"
                                  class="form-control text-editor @error('body') is-invalid @enderror"
                                  rows="6">{{ old('body', $thisData->body) }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('body') }}</div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="col-md-6">
                        <label class="form-label" for="status">{{ __('Status') }}</label> *
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            @foreach(['send-now','draft', 'sent', 'inbox', 'failed'] as $status)
                                <option value="{{ $status }}" {{ old('status', $thisData->status,'send-now') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
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

