@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        
        <!-- Header -->
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="{{ route('tickets.kanban', $project->id) }}" class="btn btn-sm btn-icon btn-label-secondary">
                                <i class="ti ti-arrow-left"></i>
                            </a>
                            <span class="badge bg-primary" style="font-size: 0.9rem; padding: 6px 12px;">{{ $thisData->ticket_key }}</span>
                            <h5 class="mb-0">{{ $thisData->title }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4 text-end mt-2 mt-md-0">
                        <a href="{{ route('tickets.edit', [$project->id, $thisData->id]) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('tickets.kanban', $project->id) }}" class="btn btn-sm btn-label-secondary">
                            <i class="ti ti-layout-board me-1"></i>Kanban
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="ti ti-file-text me-2"></i>Description</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($thisData->description ?? 'No description provided.')) !!}
                        </div>
                    </div>
                </div>

                <!-- Labels Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0"><i class="ti ti-tag me-2"></i>Labels</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLabelModal">
                                <i class="ti ti-plus me-1"></i>Add Label
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="labels-container">
                            @forelse($thisData->labels as $label)
                                <span class="badge d-flex align-items-center gap-1" style="background-color: {{ $label->color }}; font-size: 0.85rem; padding: 6px 10px;">
                                    {{ $label->name }}
                                    <i class="ti ti-x cursor-pointer" onclick="removeLabel({{ $label->id }})" style="font-size: 0.9rem;"></i>
                                </span>
                            @empty
                                <span class="text-muted">No labels yet</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Checklists Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">
                                <i class="ti ti-checkbox me-2"></i>Checklist
                                @if($thisData->checklists->count() > 0)
                                    @php
                                        $completed = $thisData->checklists->where('is_completed', true)->count();
                                        $total = $thisData->checklists->count();
                                        $percentage = round(($completed / $total) * 100);
                                    @endphp
                                    <span class="badge bg-label-{{ $percentage == 100 ? 'success' : 'info' }}">{{ $completed }}/{{ $total }}</span>
                                @endif
                            </h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addChecklistModal">
                                <i class="ti ti-plus me-1"></i>Add Item
                            </button>
                        </div>
                        
                        @if($thisData->checklists->count() > 0)
                            <!-- Progress Bar -->
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-{{ $percentage == 100 ? 'success' : 'primary' }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <div id="checklist-container">
                                @foreach($thisData->checklists as $checklist)
                                    <div class="form-check mb-2 checklist-item" data-id="{{ $checklist->id }}">
                                        <input class="form-check-input checklist-checkbox" type="checkbox" 
                                               id="checklist-{{ $checklist->id }}" 
                                               data-id="{{ $checklist->id }}"
                                               {{ $checklist->is_completed ? 'checked' : '' }}>
                                        <label class="form-check-label {{ $checklist->is_completed ? 'text-decoration-line-through text-muted' : '' }}" for="checklist-{{ $checklist->id }}">
                                            {{ $checklist->title }}
                                        </label>
                                        <i class="ti ti-trash text-danger cursor-pointer float-end" onclick="deleteChecklist({{ $checklist->id }})"></i>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No checklist items yet</p>
                        @endif
                    </div>
                </div>

                <!-- Activity & Comments Tabs -->
                <div class="card mb-3">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#comments-tab" role="tab">
                                    <i class="ti ti-message me-1"></i>Comments ({{ $thisData->comments->count() }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#activity-tab" role="tab">
                                    <i class="ti ti-history me-1"></i>Activity ({{ $thisData->activities->count() }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#attachments-tab" role="tab">
                                    <i class="ti ti-paperclip me-1"></i>Attachments ({{ $thisData->attachments->count() }})
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Comments Tab -->
                            <div class="tab-pane fade show active" id="comments-tab" role="tabpanel">
                                <!-- Add Comment Form -->
                                <form id="addCommentForm" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $thisData->id }}">
                                    <div class="mb-2">
                                        <textarea name="comment" id="comment-text" rows="3" class="form-control" placeholder="Add a comment..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-send me-1"></i>Post Comment
                                    </button>
                                </form>

                                <!-- Comments List -->
                                <div id="comments-container">
                                    @forelse($thisData->comments->sortByDesc('created_at') as $comment)
                                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom comment-item" data-id="{{ $comment->id }}">
                                            <div class="avatar avatar-md">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @if(auth()->id() == $comment->user_id)
                                                        <button type="button" class="btn btn-sm btn-icon" onclick="deleteComment({{ $comment->id }})">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No comments yet. Be the first to comment!</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Activity Tab -->
                            <div class="tab-pane fade" id="activity-tab" role="tabpanel">
                                <div class="timeline">
                                    @forelse($thisData->activities as $activity)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex gap-3">
                                                <div class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle bg-label-info">
                                                        <i class="ti ti-{{ 
                                                            $activity->action == 'created' ? 'plus' : 
                                                            ($activity->action == 'status_changed' ? 'flag' : 
                                                            ($activity->action == 'label_added' ? 'tag' : 
                                                            ($activity->action == 'label_removed' ? 'tag-off' : 'edit'))) 
                                                        }}"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-0">
                                                        <strong>{{ $activity->user->name }}</strong> {{ $activity->description }}
                                                    </p>
                                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No activity yet</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Attachments Tab -->
                            <div class="tab-pane fade" id="attachments-tab" role="tabpanel">
                                @forelse($thisData->attachments as $attachment)
                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">
                                        <div>
                                            <i class="ti ti-file me-2"></i>
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                                {{ $attachment->file_name }}
                                            </a>
                                            <small class="text-muted d-block">
                                                Uploaded by {{ $attachment->user->name }} • {{ $attachment->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        @if(auth()->id() == $attachment->user_id)
                                            <button type="button" class="btn btn-sm btn-icon">
                                                <i class="ti ti-trash text-danger"></i>
                                            </button>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted text-center">No attachments yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Details Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>Details</h6>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Status</small>
                            <span class="badge" style="background-color: {{ $thisData->ticketStatus->color ?? '#6c757d' }}; padding: 6px 12px;">
                                {{ $thisData->ticketStatus->name ?? 'N/A' }}
                            </span>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Priority</small>
                            @php
                                $priorityColors = [
                                    'low' => 'bg-success',
                                    'medium' => 'bg-warning',
                                    'high' => 'bg-danger',
                                    'critical' => 'bg-danger'
                                ];
                                $priorityIcons = [
                                    'low' => '🟢',
                                    'medium' => '🟡',
                                    'high' => '🟠',
                                    'critical' => '🔴'
                                ];
                            @endphp
                            <span class="badge {{ $priorityColors[$thisData->priority] ?? 'bg-secondary' }}" style="padding: 6px 12px;">
                                {{ $priorityIcons[$thisData->priority] ?? '' }} {{ ucfirst($thisData->priority) }}
                            </span>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Type</small>
                            @php
                                $typeIcons = [
                                    'bug' => '🐛',
                                    'task' => '✅',
                                    'story' => '📖',
                                    'epic' => '🚀'
                                ];
                            @endphp
                            <span class="badge bg-label-info" style="padding: 6px 12px;">
                                {{ $typeIcons[$thisData->type] ?? '' }} {{ ucfirst($thisData->type) }}
                            </span>
                        </div>

                        <hr>

                        <!-- Assignee -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1"><i class="ti ti-user me-1"></i>Assignee</small>
                            @if($thisData->assignee)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-xs">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($thisData->assignee->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span>{{ $thisData->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </div>

                        <!-- Reporter -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1"><i class="ti ti-user-check me-1"></i>Reporter</small>
                            @if($thisData->reporter)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-xs">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                            {{ strtoupper(substr($thisData->reporter->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span>{{ $thisData->reporter->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Project -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1"><i class="ti ti-folder me-1"></i>Project</small>
                            <strong>{{ $project->name ?? 'N/A' }}</strong>
                        </div>

                        <!-- Due Date -->
                        @if($thisData->due_date)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="ti ti-calendar me-1"></i>Due Date</small>
                                @php
                                    $isOverdue = $thisData->due_date->isPast();
                                    $isDueSoon = !$isOverdue && $thisData->due_date->diffInDays(now()) <= 3;
                                @endphp
                                <span class="badge {{ $isOverdue ? 'bg-danger' : ($isDueSoon ? 'bg-warning' : 'bg-info') }}">
                                    {{ $thisData->due_date->format('M d, Y') }}
                                    @if($isOverdue)
                                        (Overdue)
                                    @elseif($isDueSoon)
                                        (Due Soon)
                                    @endif
                                </span>
                            </div>
                        @endif

                        <!-- Story Points -->
                        @if($thisData->story_points)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="ti ti-star me-1"></i>Story Points</small>
                                <strong>{{ $thisData->story_points }}</strong>
                            </div>
                        @endif

                        <hr>

                        <!-- Timestamps -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1"><i class="ti ti-clock me-1"></i>Created</small>
                            {{ $thisData->created_at->format('M d, Y H:i') }}
                            <small class="text-muted d-block">({{ $thisData->created_at->diffForHumans() }})</small>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted d-block mb-1"><i class="ti ti-clock-edit me-1"></i>Last Updated</small>
                            {{ $thisData->updated_at->format('M d, Y H:i') }}
                            <small class="text-muted d-block">({{ $thisData->updated_at->diffForHumans() }})</small>
                        </div>
                    </div>
                </div>

                <!-- Watchers Card -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="ti ti-eye me-2"></i>Watchers ({{ $thisData->watchers->count() }})</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWatcherModal">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                    <div class="card-body" id="watchers-container">
                        @forelse($thisData->watchers as $watcher)
                            <div class="d-flex justify-content-between align-items-center mb-2 watcher-item" data-id="{{ $watcher->id }}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-xs">
                                        <span class="avatar-initial rounded-circle bg-label-info">
                                            {{ strtoupper(substr($watcher->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span>{{ $watcher->name }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-icon" onclick="removeWatcher({{ $watcher->id }})">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        @empty
                            <p class="text-muted mb-0 text-center">No watchers</p>
                        @endforelse
                    </div>
                </div>

                <!-- Time Tracking Card -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="ti ti-clock me-2"></i>Time Tracking</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#logTimeModal">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        @php
                            $totalMinutes = $thisData->timeLogs->sum('time_spent');
                            $hours = floor($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                        @endphp
                        <div class="text-center mb-3">
                            <h3 class="mb-0">{{ $hours }}h {{ $minutes }}m</h3>
                            <small class="text-muted">Total Time Logged</small>
                        </div>
                        
                        @if($thisData->timeLogs->count() > 0)
                            <hr>
                            <div class="time-logs">
                                @foreach($thisData->timeLogs->take(5) as $log)
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ floor($log->time_spent / 60) }}h {{ $log->time_spent % 60 }}m</strong>
                                            <small class="text-muted d-block">{{ $log->user->name }}</small>
                                            @if($log->description)
                                                <small class="text-muted">{{ Str::limit($log->description, 40) }}</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $log->logged_date->format('M d') }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Label Modal -->
    <div class="modal fade" id="addLabelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select id="label-select" class="form-control">
                        <option value="">Select a label</option>
                        @foreach($labels ?? [] as $label)
                            <option value="{{ $label->id }}" data-color="{{ $label->color }}">{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addLabel()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Checklist Modal -->
    <div class="modal fade" id="addChecklistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Checklist Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addChecklistForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ticket_id" value="{{ $thisData->id }}">
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Watcher Modal -->
    <div class="modal fade" id="addWatcherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Watcher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select id="watcher-select" class="form-control">
                        <option value="">Select a user</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addWatcher()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Time Modal -->
    <div class="modal fade" id="logTimeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="logTimeForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ticket_id" value="{{ $thisData->id }}">
                        <div class="mb-3">
                            <label class="form-label">Time Spent (minutes) *</label>
                            <input type="number" name="time_spent" class="form-control" required min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="logged_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Log Time</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
const ticketId = {{ $thisData->id }};

// Add Comment
document.getElementById('addCommentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("ticket-comments.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});

// Delete Comment
function deleteComment(commentId) {
    if (confirm('Delete this comment?')) {
        fetch(`/{{ getSystemPrefix() }}/ticket-comments/${commentId}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
        })
        .then(() => location.reload());
    }
}

// Add Label
function addLabel() {
    const labelId = document.getElementById('label-select').value;
    if (!labelId) return alert('Please select a label');
    
    fetch(`/{{ getSystemPrefix() }}/tickets/${ticketId}/labels`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({label_id: labelId})
    })
    .then(() => location.reload());
}

// Remove Label
function removeLabel(labelId) {
    fetch(`/{{ getSystemPrefix() }}/tickets/${ticketId}/labels/${labelId}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': csrfToken}
    })
    .then(() => location.reload());
}

// Add Checklist
document.getElementById('addChecklistForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("ticket-checklists.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken},
        body: formData
    })
    .then(() => location.reload());
});

// Toggle Checklist
document.querySelectorAll('.checklist-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checklistId = this.dataset.id;
        fetch(`/{{ getSystemPrefix() }}/ticket-checklists/${checklistId}`, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify({is_completed: this.checked})
        })
        .then(() => location.reload());
    });
});

// Delete Checklist
function deleteChecklist(checklistId) {
    if (confirm('Delete this checklist item?')) {
        fetch(`/{{ getSystemPrefix() }}/ticket-checklists/${checklistId}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': csrfToken}
        })
        .then(() => location.reload());
    }
}

// Add Watcher
function addWatcher() {
    const userId = document.getElementById('watcher-select').value;
    if (!userId) return alert('Please select a user');
    
    fetch('{{ route("ticket-watchers.store") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({ticket_id: ticketId, user_id: userId})
    })
    .then(() => location.reload());
}

// Remove Watcher
function removeWatcher(userId) {
    fetch('{{ route("ticket-watchers.destroy") }}', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({ticket_id: ticketId, user_id: userId})
    })
    .then(() => location.reload());
}

// Log Time
document.getElementById('logTimeForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("time-logs.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken},
        body: formData
    })
    .then(() => location.reload());
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}
.timeline-item {
    position: relative;
    padding-left: 0;
}
.comment-item, .watcher-item {
    transition: background 0.2s;
}
.comment-item:hover, .watcher-item:hover {
    background: #f8f9fa;
    border-radius: 6px;
}
</style>
@endsection
