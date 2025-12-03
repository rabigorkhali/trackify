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
                            <i class="ti ti-bell me-2"></i>Notifications
                        </h4>
                        <p class="text-muted mb-0 mt-1">Stay updated with ticket activities</p>
                    </div>
                    <div class="col-md-6 text-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                            <i class="ti ti-mail-opened me-1"></i>Mark All as Read
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearReadNotifications()">
                            <i class="ti ti-trash me-1"></i>Clear Read
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card">
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ !$notification->read ? 'bg-label-primary' : '' }}" 
                                 style="cursor: pointer;"
                                 onclick="window.location.href='{{ $notification->ticket ? route('tickets.show', [$notification->project_id, $notification->ticket_id]) : '#' }}'">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-initial rounded-circle bg-label-{{ $notification->color }}">
                                                <i class="ti {{ $notification->icon }}"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 {{ !$notification->read ? 'fw-bold' : '' }}">
                                                    {{ $notification->title }}
                                                </h6>
                                                <p class="mb-1">{{ $notification->message }}</p>
                                                <div class="d-flex align-items-center gap-2 text-muted">
                                                    <small>
                                                        <i class="ti ti-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                    @if($notification->triggeredBy)
                                                        <small>
                                                            <i class="ti ti-user me-1"></i>{{ $notification->triggeredBy->name }}
                                                        </small>
                                                    @endif
                                                    @if($notification->ticket)
                                                        <small>
                                                            <i class="ti ti-ticket me-1"></i>{{ $notification->ticket->title }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!$notification->read)
                                                <span class="badge badge-dot bg-primary"></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-bell-off" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No notifications found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function markAllAsRead() {
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function clearReadNotifications() {
        if (confirm('Are you sure you want to delete all read notifications?')) {
            fetch('{{ route('notifications.clear-read') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endsection

