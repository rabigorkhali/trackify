@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')

        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <span><i class="ti ti-tag me-2"></i>{{ $title }}</span>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLabelModal">
                    <i class="ti ti-plus me-1"></i>Create Label
                </button>
            </h5>

            <div class="card-body">
                <!-- Search and Filter -->
                <form method="get" action="{{ route('ticket-labels.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="keyword" class="form-control" placeholder="Search labels..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i>Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Labels Grid -->
                <div class="row g-3">
                    @forelse($thisDatas as $label)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge" style="background-color: {{ $label->color }}; font-size: 0.9rem; padding: 8px 14px;">
                                            {{ $label->name }}
                                        </span>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item edit-label-btn" href="javascript:void(0);" 
                                                   data-id="{{ $label->id }}"
                                                   data-name="{{ $label->name }}"
                                                   data-color="{{ $label->color }}"
                                                   data-description="{{ $label->description }}"
                                                   data-bs-toggle="modal" data-bs-target="#editLabelModal">
                                                    <i class="ti ti-pencil me-1"></i>Edit
                                                </a>
                                                <button type="button" class="dropdown-item text-danger delete-confirm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        data-action="{{ route('ticket-labels.destroy', $label->id) }}">
                                                    <i class="ti ti-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @if($label->description)
                                        <p class="text-muted mb-2" style="font-size: 0.85rem;">{{ $label->description }}</p>
                                    @endif
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="ti ti-calendar me-1"></i>
                                        Created: {{ $label->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>No labels found. Create your first label to get started!
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($thisDatas->hasPages())
                    <div class="mt-4">
                        {{ $thisDatas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Label Modal -->
    <div class="modal fade" id="createLabelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-tag me-2"></i>Create New Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('ticket-labels.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="create_name">Label Name *</label>
                            <input required type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Bug, Feature, Documentation">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="create_color">Color *</label>
                            <div class="input-group">
                                <input required type="color" name="color" id="create_color" class="form-control form-control-color" value="#6c757d">
                                <input type="text" id="create_color_text" class="form-control" value="#6c757d" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="create_description">Description</label>
                            <textarea name="description" id="create_description" rows="3" class="form-control" placeholder="Optional description..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input type="radio" id="create_status_active" name="status" value="1" checked class="form-check-input">
                                <label for="create_status_active" class="form-check-label">Active</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="create_status_inactive" name="status" value="0" class="form-check-input">
                                <label for="create_status_inactive" class="form-check-label">Inactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Label</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Label Modal -->
    <div class="modal fade" id="editLabelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-pencil me-2"></i>Edit Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLabelForm" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit_name">Label Name *</label>
                            <input required type="text" name="name" id="edit_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_color">Color *</label>
                            <div class="input-group">
                                <input required type="color" name="color" id="edit_color" class="form-control form-control-color">
                                <input type="text" id="edit_color_text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_description">Description</label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input type="radio" id="edit_status_active" name="status" value="1" class="form-check-input">
                                <label for="edit_status_active" class="form-check-label">Active</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="edit_status_inactive" name="status" value="0" class="form-check-input">
                                <label for="edit_status_inactive" class="form-check-label">Inactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Label</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker sync
    document.getElementById('create_color').addEventListener('input', function() {
        document.getElementById('create_color_text').value = this.value;
    });

    document.getElementById('edit_color').addEventListener('input', function() {
        document.getElementById('edit_color_text').value = this.value;
    });

    // Edit label button handler
    document.querySelectorAll('.edit-label-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const color = this.getAttribute('data-color');
            const description = this.getAttribute('data-description');

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_color').value = color;
            document.getElementById('edit_color_text').value = color;
            document.getElementById('edit_description').value = description || '';

            document.getElementById('editLabelForm').action = '{{ url(getSystemPrefix() . "/ticket-labels") }}/' + id;
        });
    });
});
</script>
@endsection

