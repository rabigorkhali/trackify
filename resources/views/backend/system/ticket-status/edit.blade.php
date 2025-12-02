@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="ti ti-pencil me-2"></i>Edit Ticket Status
                        </h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('ticket-statuses.index') }}" class="btn btn-label-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ticket-statuses.update', $ticketStatus->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Status Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $ticketStatus->name) }}" 
                                   placeholder="e.g., To Do, In Progress"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" 
                                       class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" 
                                       name="color" 
                                       value="{{ old('color', $ticketStatus->color) }}" 
                                       required
                                       style="max-width: 80px;">
                                <input type="text" 
                                       class="form-control" 
                                       id="color-text" 
                                       value="{{ old('color', $ticketStatus->color) }}" 
                                       readonly>
                            </div>
                            @error('color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This color will be used to identify the status in the kanban board</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="1" {{ old('status', $ticketStatus->status) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $ticketStatus->status) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($ticketStatus->tickets->count() > 0)
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    This status is currently used by <strong>{{ $ticketStatus->tickets->count() }}</strong> ticket(s).
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Update Status
                        </button>
                        <a href="{{ route('ticket-statuses.index') }}" class="btn btn-label-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color');
        const colorText = document.getElementById('color-text');
        
        // Update text input when color picker changes
        colorInput.addEventListener('input', function() {
            colorText.value = this.value;
        });
        
        // Update color picker when text input changes
        colorText.addEventListener('input', function() {
            colorInput.value = this.value;
        });
    });
</script>
@endsection

