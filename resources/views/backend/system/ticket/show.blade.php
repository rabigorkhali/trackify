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
                            <h5 class="mb-0 editable-title" id="ticket-title" contenteditable="true" 
                                style="border-bottom: 1px dashed transparent; padding: 2px 4px; cursor: text;"
                                onblur="updateTitle(this.innerText)">{{ $thisData->title }}</h5>
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
                        <h6 class="card-title"><i class="ti ti-file-text me-2"></i>Description <small class="text-muted">(Click to edit)</small></h6>
                        <textarea id="ticket-description" class="form-control" rows="4" 
                                  onblur="updateDescription(this.value)"
                                  style="min-height: 100px;">{{ $thisData->description ?? 'No description provided.' }}</textarea>
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
                                <form id="addCommentForm" class="mb-4" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $thisData->id }}">
                                    
                                    <div class="mb-2 position-relative">
                                        <label class="form-label small"><i class="ti ti-message me-1"></i>Add Comment</label>
                                        <textarea name="comment" id="comment-text" rows="4" class="form-control" 
                                                  placeholder="Add a comment... (Type @ to mention someone)" required></textarea>
                                        <!-- Mention Dropdown -->
                                        <div id="mention-dropdown" class="position-absolute bg-white border rounded shadow-sm" 
                                             style="display: none; max-height: 200px; overflow-y: auto; z-index: 1000; width: 250px;"></div>
                                    </div>
                                    
                                    <!-- File Upload (Multiple) -->
                                    <div class="mb-3">
                                        <label for="comment_attachments" class="form-label small">
                                            <i class="ti ti-paperclip me-1"></i>Attachments (optional)
                                        </label>
                                        <input type="file" class="form-control form-control-sm" id="comment_attachments" 
                                               name="attachments[]" 
                                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.xlsx,.xls,.zip" 
                                               multiple>
                                        <div class="form-text">
                                            Max 10MB per file. Multiple files allowed. 
                                            <strong>Formats:</strong> JPG, PNG, GIF, PDF, DOC, DOCX, TXT, XLSX, XLS, ZIP
                                        </div>
                                        <div id="file-preview" class="mt-2"></div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm" id="submit-comment-btn">
                                            <i class="ti ti-send me-1"></i>Post Comment
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('addCommentForm').reset(); document.getElementById('file-preview').innerHTML = '';">
                                            <i class="ti ti-x me-1"></i>Clear
                                        </button>
                                    </div>
                                </form>

                                <!-- Comments List -->
                                <div id="comments-container">
                                    @forelse($thisData->comments->sortByDesc('created_at') as $comment)
                                        <div class="card mb-3 comment-item" data-id="{{ $comment->id }}">
                                            <div class="card-body">
                                                <div class="d-flex gap-3">
                                                    <div class="avatar avatar-md">
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                                <small class="text-muted">
                                                                    <i class="ti ti-clock-hour-4 me-1"></i>{{ $comment->created_at->format('M d, Y \a\t h:i A') }}
                                                                    <span class="mx-1">•</span>
                                                                    {{ $comment->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                            @if(auth()->id() == $comment->user_id)
                                                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                                                        onclick="deleteComment({{ $comment->id }})"
                                                                        title="Delete comment">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <div class="comment-text p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $comment->comment }}</div>
                                                        
                                                        <!-- Comment Attachments -->
                                                        @if($comment->attachments && is_array($comment->attachments) && count($comment->attachments) > 0)
                                                            <div class="mt-3">
                                                                <small class="text-muted d-block mb-2">
                                                                    <i class="ti ti-paperclip me-1"></i>Attachments ({{ count($comment->attachments) }})
                                                                </small>
                                                                <div class="row g-2">
                                                                    @foreach($comment->attachments as $attachment)
                                                                        @php
                                                                            $fileName = is_array($attachment) ? ($attachment['original_name'] ?? $attachment['name'] ?? 'file') : $attachment;
                                                                            $filePath = is_array($attachment) ? ($attachment['path'] ?? '') : $attachment;
                                                                            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                                        @endphp
                                                                        <div class="col-md-{{ $isImage ? '4' : '6' }}">
                                                                            @if($isImage)
                                                                                <a href="{{ url('/') }}/{{ $filePath }}" target="_blank" class="d-block">
                                                                                    <img src="{{ url('/') }}/{{ $filePath }}" 
                                                                                         class="img-fluid rounded border" 
                                                                                         alt="{{ $fileName }}"
                                                                                         style="max-height: 120px; object-fit: cover; width: 100%; cursor: pointer;"
                                                                                         title="Click to view full size">
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ url('/') }}/{{ $filePath }}" 
                                                                                   download="{{ $fileName }}"
                                                                                   class="btn btn-sm btn-outline-primary w-100 text-start d-flex align-items-center gap-2"
                                                                                   title="Download {{ $fileName }}">
                                                                                    <i class="ti ti-download"></i>
                                                                                    <span class="text-truncate">{{ Str::limit($fileName, 25) }}</span>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="ti ti-message-off" style="font-size: 3rem; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2">No comments yet. Be the first to comment!</p>
                                        </div>
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
                            <label class="form-label small text-muted mb-1"><i class="ti ti-flag me-1"></i>Status</label>
                            <select class="form-select form-select-sm quick-update-field" name="ticket_status_id" data-field="ticket_status_id">
                                @foreach(\App\Models\TicketStatus::where('status', 1)->orderBy('order')->get() as $status)
                                    <option value="{{ $status->id }}" {{ $thisData->ticket_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1"><i class="ti ti-alert-circle me-1"></i>Priority</label>
                            <select class="form-select form-select-sm quick-update-field" name="priority" data-field="priority">
                                <option value="low" {{ $thisData->priority == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ $thisData->priority == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ $thisData->priority == 'high' ? 'selected' : '' }}>🟠 High</option>
                                <option value="critical" {{ $thisData->priority == 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1"><i class="ti ti-category me-1"></i>Type</label>
                            <select class="form-select form-select-sm quick-update-field" name="type" data-field="type">
                                <option value="bug" {{ $thisData->type == 'bug' ? 'selected' : '' }}>🐛 Bug</option>
                                <option value="task" {{ $thisData->type == 'task' ? 'selected' : '' }}>✅ Task</option>
                                <option value="story" {{ $thisData->type == 'story' ? 'selected' : '' }}>📖 Story</option>
                                <option value="epic" {{ $thisData->type == 'epic' ? 'selected' : '' }}>🚀 Epic</option>
                            </select>
                        </div>

                        <hr>

                        <!-- Assignee -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1"><i class="ti ti-user me-1"></i>Assignee</label>
                            <select class="form-select form-select-sm quick-update-field" name="assignee_id" data-field="assignee_id">
                                <option value="">Unassigned</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $thisData->assignee_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
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
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1"><i class="ti ti-calendar me-1"></i>Due Date</label>
                            <input type="date" class="form-control form-control-sm quick-update-field" 
                                   name="due_date" data-field="due_date" 
                                   value="{{ $thisData->due_date ? $thisData->due_date->format('Y-m-d') : '' }}">
                        </div>

                        <!-- Story Points -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1"><i class="ti ti-star me-1"></i>Story Points</label>
                            <input type="number" class="form-control form-control-sm quick-update-field" 
                                   name="story_points" data-field="story_points" min="0" step="0.5"
                                   value="{{ $thisData->story_points ?? '' }}" placeholder="e.g., 3, 5, 8">
                        </div>

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

// User mention autocomplete
const users = @json(\App\Models\User::select('id', 'name', 'email')->get());
const commentTextarea = document.getElementById('comment-text');
const mentionDropdown = document.getElementById('mention-dropdown');
let mentionActive = false;
let mentionStart = 0;
let selectedMentionIndex = -1;

commentTextarea?.addEventListener('input', function(e) {
    const cursorPos = this.selectionStart;
    const textBeforeCursor = this.value.substring(0, cursorPos);
    const lastAtSymbol = textBeforeCursor.lastIndexOf('@');
    
    if (lastAtSymbol !== -1) {
        const textAfterAt = textBeforeCursor.substring(lastAtSymbol + 1);
        
        // Check if we're still in a mention (no spaces after @)
        if (!textAfterAt.includes(' ') && !textAfterAt.includes('\n')) {
            mentionActive = true;
            mentionStart = lastAtSymbol;
            const searchTerm = textAfterAt.toLowerCase();
            
            // Filter users
            const filteredUsers = users.filter(user => 
                user.name.toLowerCase().includes(searchTerm) || 
                user.email.toLowerCase().includes(searchTerm)
            ).slice(0, 5);
            
            if (filteredUsers.length > 0) {
                showMentionDropdown(filteredUsers, lastAtSymbol);
            } else {
                hideMentionDropdown();
            }
        } else {
            hideMentionDropdown();
        }
    } else {
        hideMentionDropdown();
    }
});

commentTextarea?.addEventListener('keydown', function(e) {
    if (!mentionActive || mentionDropdown.style.display === 'none') return;
    
    const items = mentionDropdown.querySelectorAll('.mention-item');
    
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedMentionIndex = Math.min(selectedMentionIndex + 1, items.length - 1);
        updateMentionSelection(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedMentionIndex = Math.max(selectedMentionIndex - 1, 0);
        updateMentionSelection(items);
    } else if (e.key === 'Enter' && selectedMentionIndex >= 0) {
        e.preventDefault();
        items[selectedMentionIndex].click();
    } else if (e.key === 'Escape') {
        hideMentionDropdown();
    }
});

function showMentionDropdown(filteredUsers, atPosition) {
    selectedMentionIndex = 0;
    mentionDropdown.innerHTML = '';
    
    filteredUsers.forEach((user, index) => {
        const div = document.createElement('div');
        div.className = 'mention-item p-2 cursor-pointer' + (index === 0 ? ' selected' : '');
        div.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xs me-2">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        ${user.name.substring(0, 2).toUpperCase()}
                    </span>
                </div>
                <div>
                    <div class="fw-semibold">${user.name}</div>
                    <small class="text-muted">${user.email}</small>
                </div>
            </div>
        `;
        
        div.addEventListener('click', function() {
            insertMention(user.name);
            hideMentionDropdown();
        });
        
        div.addEventListener('mouseenter', function() {
            selectedMentionIndex = index;
            updateMentionSelection(mentionDropdown.querySelectorAll('.mention-item'));
        });
        
        mentionDropdown.appendChild(div);
    });
    
    // Position dropdown
    const rect = commentTextarea.getBoundingClientRect();
    const lines = commentTextarea.value.substring(0, mentionStart).split('\n').length;
    mentionDropdown.style.top = (lines * 20 + 5) + 'px';
    mentionDropdown.style.left = '10px';
    mentionDropdown.style.display = 'block';
}

function hideMentionDropdown() {
    mentionDropdown.style.display = 'none';
    mentionActive = false;
    selectedMentionIndex = -1;
}

function updateMentionSelection(items) {
    items.forEach((item, index) => {
        if (index === selectedMentionIndex) {
            item.classList.add('selected', 'bg-light');
        } else {
            item.classList.remove('selected', 'bg-light');
        }
    });
}

function insertMention(userName) {
    const cursorPos = commentTextarea.selectionStart;
    const textBefore = commentTextarea.value.substring(0, mentionStart);
    const textAfter = commentTextarea.value.substring(cursorPos);
    const mentionText = userName.replace(/\s+/g, '');
    
    commentTextarea.value = textBefore + '@' + mentionText + ' ' + textAfter;
    
    // Set cursor position after mention
    const newCursorPos = mentionStart + mentionText.length + 2;
    commentTextarea.setSelectionRange(newCursorPos, newCursorPos);
    commentTextarea.focus();
}

// Click outside to close dropdown
document.addEventListener('click', function(e) {
    if (e.target !== commentTextarea && !mentionDropdown.contains(e.target)) {
        hideMentionDropdown();
    }
});

// File Preview
document.getElementById('comment_attachments')?.addEventListener('change', function(e) {
    const preview = document.getElementById('file-preview');
    preview.innerHTML = '';
    
    if (this.files.length > 0) {
        const fileList = document.createElement('div');
        fileList.className = 'alert alert-info p-2';
        fileList.innerHTML = '<small class="d-block mb-1"><strong>' + this.files.length + ' file(s) selected:</strong></small>';
        
        Array.from(this.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'small';
            fileItem.innerHTML = `<i class="ti ti-file me-1"></i>${file.name} <span class="text-muted">(${formatFileSize(file.size)})</span>`;
            fileList.appendChild(fileItem);
        });
        
        preview.appendChild(fileList);
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Add Comment
document.getElementById('addCommentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submit-comment-btn');
    
    console.log('Submitting comment...');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Posting...';
    
    fetch('{{ route("ticket-comments.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-send me-1"></i>Post Comment';
        
        if (data.success) {
            console.log('Comment added successfully, reloading...');
            showToast('success', 'Comment posted successfully!');
            setTimeout(() => location.reload(), 500);
        } else {
            console.error('Failed to add comment:', data);
            showToast('error', 'Failed to add comment: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error adding comment:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-send me-1"></i>Post Comment';
        showToast('error', 'Error adding comment. Check console for details.');
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

// Quick Update Fields - Inline Editing
document.querySelectorAll('.quick-update-field').forEach(function(field) {
    field.addEventListener('change', function() {
        const fieldName = this.getAttribute('data-field');
        const fieldValue = this.value;
        quickUpdateField(fieldName, fieldValue);
    });
});

// Update Title
function updateTitle(newTitle) {
    if (newTitle.trim() === '' || newTitle === '{{ $thisData->title }}') return;
    quickUpdateField('title', newTitle);
}

// Update Description  
function updateDescription(newDescription) {
    if (newDescription === '{{ $thisData->description }}') return;
    quickUpdateField('description', newDescription);
}

// Quick field update function
function quickUpdateField(fieldName, fieldValue) {
    console.log('Quick updating:', fieldName, 'to:', fieldValue);
    
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('project_id', {{ $project->id }});
    
    // Preserve current values and update only the changed field
    formData.append('title', fieldName === 'title' ? fieldValue : '{{ $thisData->title }}');
    formData.append('description', fieldName === 'description' ? fieldValue : `{{ $thisData->description ?? '' }}`);
    formData.append('ticket_status_id', fieldName === 'ticket_status_id' ? fieldValue : {{ $thisData->ticket_status_id }});
    formData.append('priority', fieldName === 'priority' ? fieldValue : '{{ $thisData->priority }}');
    formData.append('type', fieldName === 'type' ? fieldValue : '{{ $thisData->type }}');
    formData.append('assignee_id', fieldName === 'assignee_id' ? fieldValue : '{{ $thisData->assignee_id ?? '' }}');
    formData.append('due_date', fieldName === 'due_date' ? fieldValue : '{{ $thisData->due_date ? $thisData->due_date->format('Y-m-d') : '' }}');
    formData.append('story_points', fieldName === 'story_points' ? fieldValue : '{{ $thisData->story_points ?? '' }}');
    formData.append('status', {{ $thisData->status }});
    
    // Show loading toast
    showToast('info', 'Updating...');
    
    fetch(`/{{ getSystemPrefix() }}/projects/{{ $project->id }}/tickets/{{ $thisData->id }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showToast('success', ucfirst(fieldName.replace('_', ' ')) + ' updated!');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Update failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to update');
    });
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Quick Update Fields - Inline Editing
document.querySelectorAll('.quick-update-field').forEach(function(field) {
    field.addEventListener('change', function() {
        const fieldName = this.getAttribute('data-field');
        const fieldValue = this.value;
        updateTicketField(ticketId, fieldName, fieldValue);
    });
});

function updateTicketField(ticketId, fieldName, fieldValue) {
    console.log('Updating field:', fieldName, 'to:', fieldValue);
    
    // Show loading indicator
    const field = document.querySelector(`[data-field="${fieldName}"]`);
    const originalValue = field.value;
    field.disabled = true;
    
    // Fetch current ticket data
    fetch(`/{{ getSystemPrefix() }}/projects/{{ $project->id }}/tickets/${ticketId}/show`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.ticket) {
            const ticket = data.ticket;
            
            // Prepare form data with all required fields
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('project_id', {{ $project->id }});
            formData.append('title', ticket.title);
            formData.append('description', ticket.description || '');
            formData.append('ticket_status_id', fieldName === 'ticket_status_id' ? fieldValue : ticket.ticket_status_id);
            formData.append('priority', fieldName === 'priority' ? fieldValue : ticket.priority);
            formData.append('type', fieldName === 'type' ? fieldValue : ticket.type);
            formData.append('assignee_id', fieldName === 'assignee_id' ? (fieldValue || '') : (ticket.assignee_id || ''));
            formData.append('due_date', fieldName === 'due_date' ? (fieldValue || '') : (ticket.due_date || ''));
            formData.append('story_points', fieldName === 'story_points' ? (fieldValue || '') : (ticket.story_points || ''));
            formData.append('status', ticket.status);
            
            // Send update request
            return fetch(`/{{ getSystemPrefix() }}/projects/{{ $project->id }}/tickets/${ticketId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
        }
    })
    .then(response => {
        field.disabled = false;
        if (response && (response.ok || response.redirected)) {
            showToast('success', 'Updated successfully!');
            // Reload page to reflect changes
            setTimeout(() => location.reload(), 800);
        } else {
            throw new Error('Update failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        field.value = originalValue;
        field.disabled = false;
        showToast('error', 'Failed to update');
    });
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'x'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
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
/* Mention Autocomplete Styles */
#mention-dropdown {
    max-width: 300px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.mention-item {
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f0f0f0;
}
.mention-item:last-child {
    border-bottom: none;
}
.mention-item:hover, .mention-item.selected {
    background-color: #f8f9fa;
}
/* Editable Title Styles */
.editable-title:hover {
    border-bottom-color: #696cff !important;
    background-color: rgba(105, 108, 255, 0.04);
}
.editable-title:focus {
    outline: none;
    border-bottom-color: #696cff !important;
    background-color: rgba(105, 108, 255, 0.08);
}
</style>
@endsection
