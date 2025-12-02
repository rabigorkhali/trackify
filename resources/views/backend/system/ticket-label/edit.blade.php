@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header"><i class="ti ti-pencil me-2"></i>{{ $title }}</h5>
            <div class="card-body">
                <form action="{{ route('ticket-labels.update', $thisData->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    @include('backend.system.ticket-label.form')
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Update Label
                        </button>
                        <a href="{{ route('ticket-labels.index') }}" class="btn btn-label-secondary">
                            <i class="ti ti-x me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

