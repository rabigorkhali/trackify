@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        
        <!-- User Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-xl">
                                <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2rem;">
                                    {{ strtoupper(substr($thisData->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $thisData->name }}</h4>
                                <p class="mb-0 text-muted">
                                    <i class="ti ti-mail me-1"></i>{{ $thisData->email }}
                                </p>
                                <div class="mt-1">
                                    @foreach($thisData->roles as $role)
                                        <span class="badge bg-label-info">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end mt-3 mt-md-0">
                        @if(auth()->id() == $thisData->id || hasPermission('/users/*', 'put'))
                            <a href="{{ route('users.edit', $thisData->id) }}" class="btn btn-primary">
                                <i class="ti ti-pencil me-1"></i>Edit Profile
                            </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stats Cards -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-clipboard-check ti-lg"></i>
                            </span>
                        </div>
                        <span class="d-block mb-1">Assigned Tickets</span>
                        <h3 class="card-title mb-1">{{ $assignedTickets->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-flag ti-lg"></i>
                            </span>
                        </div>
                        <span class="d-block mb-1">Reported Tickets</span>
                        <h3 class="card-title mb-1">{{ $reportedTickets->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-message ti-lg"></i>
                            </span>
                        </div>
                        <span class="d-block mb-1">Comments</span>
                        <h3 class="card-title mb-1">{{ $recentComments->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ti ti-activity ti-lg"></i>
                            </span>
                        </div>
                        <span class="d-block mb-1">Activities</span>
                        <h3 class="card-title mb-1">{{ $recentActivities->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Assigned Tickets -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="ti ti-clipboard-check me-2"></i>Assigned Tickets</h5>
                    </div>
                    <div class="card-body">
                        @forelse($assignedTickets as $ticket)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <a href="{{ route('tickets.show', [$ticket->project_id, $ticket->id]) }}" class="text-decoration-none">
                                        <span class="badge bg-label-primary me-2">{{ $ticket->ticket_key }}</span>
                                        <strong>{{ $ticket->title }}</strong>
                                    </a>
                                    <div class="text-muted small mt-1">
                                        <i class="ti ti-folder me-1"></i>{{ $ticket->project->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <span class="badge" style="background-color: {{ $ticket->ticketStatus->color ?? '#6c757d' }}">
                                    {{ $ticket->ticketStatus->name ?? 'N/A' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">No assigned tickets</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Reported Tickets -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="ti ti-flag me-2"></i>Reported Tickets</h5>
                    </div>
                    <div class="card-body">
                        @forelse($reportedTickets as $ticket)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <a href="{{ route('tickets.show', [$ticket->project_id, $ticket->id]) }}" class="text-decoration-none">
                                        <span class="badge bg-label-primary me-2">{{ $ticket->ticket_key }}</span>
                                        <strong>{{ $ticket->title }}</strong>
                                    </a>
                                    <div class="text-muted small mt-1">
                                        <i class="ti ti-folder me-1"></i>{{ $ticket->project->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <span class="badge" style="background-color: {{ $ticket->ticketStatus->color ?? '#6c757d' }}">
                                    {{ $ticket->ticketStatus->name ?? 'N/A' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">No reported tickets</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-message me-2"></i>Recent Comments</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($recentComments as $comment)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ route('tickets.show', [$comment->ticket->project_id, $comment->ticket_id]) }}" class="text-decoration-none">
                                        <span class="badge bg-label-info">{{ $comment->ticket->ticket_key }}</span>
                                    </a>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 small">{{ Str::limit($comment->comment, 150) }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No comments yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-activity me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($recentActivities as $activity)
                            <div class="mb-2 pb-2 border-bottom">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ti ti-point" style="font-size: 0.9rem; margin-top: 2px;"></i>
                                    <div class="flex-grow-1">
                                        <div class="small">
                                            {{ $activity->description }}
                                            <a href="{{ route('tickets.show', [$activity->ticket->project_id, $activity->ticket_id]) }}" class="text-decoration-none">
                                                <span class="badge bg-label-info">{{ $activity->ticket->ticket_key }}</span>
                                            </a>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $activity->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No recent activity</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ti ti-info-circle me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong> {{ $thisData->name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong> {{ $thisData->email }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong> 
                        <span class="badge {{ $thisData->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $thisData->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Roles:</strong>
                        @foreach($thisData->roles as $role)
                            <span class="badge bg-label-info">{{ $role->name }}</span>
                        @endforeach
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Member Since:</strong> {{ $thisData->created_at->format('M d, Y') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong> {{ $thisData->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

