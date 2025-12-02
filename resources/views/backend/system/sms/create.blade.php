@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')

        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('sms.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-12">
                        <label class="form-label" for="title">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="SMS title">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Receiver (Multi-select) -->
                    <div class="col-md-12">
                        <label class="form-label" for="receiver">{{ __('Receiver') }}</label> *
                        <button type="button" id="select-all" class="btn btn-sm btn-primary m-2">{{ __('Select All') }}</button>

                        <select name="receiver[]" id="receiver" class="form-control select2-multiple @error('receiver') is-invalid @enderror" multiple required>
                            @foreach(getAllPhoneNumbers() as $phoneNumber)
                                <option value="{{ $phoneNumber }}" {{ collect(old('receiver'))->contains($phoneNumber) ? 'selected' : '' }}>
                                    {{ $phoneNumber }}
                                </option>
                            @endforeach
                        </select>
                        @foreach ($errors->get('receiver.*') as $index => $messages)
                            @foreach ($messages as $message)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @endforeach
                        @endforeach
                    </div>

                    <!-- Message -->
                    <div class="col-md-12">
                        <label class="form-label" for="message">{{ __('Message') }}</label> *
                        <textarea required name="message" id="message"
                                  class="form-control @error('message') is-invalid @enderror"
                                  rows="6" onkeyup="countCharacters(this)">{{ old('message') }}</textarea>
                        <small class="text-muted">
                            Character count: <span id="charCount">0</span> | 
                            Credits: <span id="creditCount">0</span>
                        </small>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Dropdown -->
                    <div class="col-md-6">
                        <label class="form-label" for="status">{{ __('Status') }}</label> *
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach(['draft', 'sent', 'send-now','failed', 'inbox'] as $status)
                                <option value="{{ $status }}" {{ old('status', 'pending') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>

                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function countCharacters(textarea) {
        const count = textarea.value.length;
        document.getElementById('charCount').textContent = count;
        
        // Calculate credits (1 credit for 160 characters, 2 for above 160, etc.)
        const credits = Math.ceil(count / 160);
        document.getElementById('creditCount').textContent = credits;
    }
    
    // Initialize count on page load
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('message');
        countCharacters(textarea);
    });
</script>
@endsection

