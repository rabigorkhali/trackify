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
                            <i class="ti ti-list-check me-2"></i>Ticket Status Management
                        </h4>
                        <p class="text-muted mb-0 mt-1">Manage and reorder ticket statuses using drag & drop</p>
                    </div>
                    <div class="col-md-6 text-end mt-3 mt-md-0">
                        <a href="{{ route('ticket-statuses.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Add New Status
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statuses List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Statuses</h5>
                <p class="text-muted mb-0 mt-1">
                    <i class="ti ti-arrows-move me-1"></i>Drag and drop to reorder
                </p>
            </div>
            <div class="card-body">
                @if($statuses->count() > 0)
                    <div id="status-list" class="list-group">
                        @foreach($statuses as $status)
                            <div class="list-group-item d-flex align-items-center justify-content-between status-item" 
                                 data-id="{{ $status->id }}" 
                                 style="cursor: move; border-left: 4px solid {{ $status->color }};">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <i class="ti ti-grip-vertical text-muted" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $status->name }}</h6>
                                        <small class="text-muted">Order: {{ $status->order }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: {{ $status->color }}; color: white;">
                                        {{ $status->color }}
                                    </span>
                                    <span class="badge {{ $status->status ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ $status->status ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="badge bg-label-info">
                                        {{ $status->tickets->count() }} tickets
                                    </span>
                                    <div class="btn-group">
                                        <a href="{{ route('ticket-statuses.edit', $status->id) }}" 
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('ticket-statuses.destroy', $status->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this status?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-icon btn-outline-danger"
                                                    title="Delete"
                                                    {{ $status->tickets->count() > 0 ? 'disabled' : '' }}>
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-list-check" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No ticket statuses found. Create one to get started.</p>
                        <a href="{{ route('ticket-statuses.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Create Status
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusList = document.getElementById('status-list');
        
        if (statusList) {
            new Sortable(statusList, {
                animation: 150,
                handle: '.status-item',
                ghostClass: 'bg-light',
                onEnd: function(evt) {
                    updateOrder();
                }
            });
        }
        
        function updateOrder() {
            const items = document.querySelectorAll('.status-item');
            const orders = Array.from(items).map(item => item.dataset.id);
            
            fetch('{{ route('ticket-statuses.update-order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('success', data.message);
                    // Reload page to update order numbers
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', 'Failed to update order');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred');
            });
        }
        
        function showToast(type, message) {
            // Simple toast notification
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    });
</script>
@endsection

