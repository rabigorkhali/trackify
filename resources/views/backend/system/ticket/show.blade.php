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
                            <a href="{{ route('kanban.index') }}" class="btn btn-sm btn-icon btn-label-secondary">
                                <i class="ti ti-arrow-left"></i>
                            </a>
                            <span class="badge bg-primary" style="font-size: 0.9rem; padding: 6px 12px;">{{ $thisData->ticket_key }}</span>
                            <div class="d-flex align-items-start gap-2 flex-grow-1" style="min-width: 0;">
                                <div id="view_ticket_title_container" class="flex-grow-1 position-relative" style="min-width: 0; padding-right: 40px;">
                                    <h5 class="mb-0 pe-2" id="view_ticket_title" style="word-break: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.4;">{{ $thisData->title }}</h5>
                                    <button type="button" class="btn btn-sm btn-icon" id="toggle-title-edit" title="Edit title" style="position: absolute; top: -5px; right: 0;">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                </div>
                                <div id="edit_ticket_title_container" style="display: none; min-width: 0;" class="flex-grow-1">
                                    <textarea id="edit_ticket_title_input" class="form-control form-control-lg" 
                                              rows="2"
                                              style="font-size: 1.25rem; font-weight: 500; resize: vertical; overflow-y: auto;">{{ $thisData->title }}</textarea>
                                    <div class="d-flex gap-2 mt-2">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="saveTitle()" title="Ctrl+Enter to save">
                                            <i class="ti ti-check me-1"></i>Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cancelTitleEdit()" title="Esc to cancel">
                                            <i class="ti ti-x me-1"></i>Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end mt-2 mt-md-0">
                        <a href="{{ route('tickets.edit', [$project->id, $thisData->id]) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('kanban.index') }}" class="btn btn-sm btn-label-secondary">
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="ti ti-file-text me-2"></i>Description</h6>
                            <button type="button" class="btn btn-sm btn-icon" id="toggle-show-description-edit" title="Edit description">
                                <i class="ti ti-pencil"></i>
                            </button>
                        </div>
                        <div id="show-view-description" class="p-3 bg-light rounded" style="min-height: 80px;">
                            {!! $thisData->description ?? 'No description provided.' !!}
                    </div>
                        <div id="show-edit-description-container" style="display: none;">
                            <div id="show-description-editor" style="min-height: 200px;"></div>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-sm btn-primary" onclick="saveShowDescription()">
                                    <i class="ti ti-check me-1"></i>Save
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cancelShowDescriptionEdit()">
                                    <i class="ti ti-x me-1"></i>Cancel
                            </button>
                        </div>
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

                <!-- Ticket Attachments Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">
                                <i class="ti ti-paperclip me-2"></i>Attachments
                                <span class="badge bg-label-secondary">{{ $thisData->attachments->count() }}</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('main-ticket-attachments-input').click()">
                                <i class="ti ti-upload me-1"></i>Upload Files
                            </button>
                        </div>
                        <input type="file" id="main-ticket-attachments-input" multiple 
                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.xlsx,.xls,.zip"
                               style="display: none;" 
                               onchange="uploadMainTicketAttachments()">
                        
                        <div class="row g-2">
                            @forelse($thisData->attachments as $attachment)
                                @php
                                    $extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="col-md-{{ $isImage ? '4' : '6' }}" id="attachment-{{ $attachment->id }}">
                                    @if($isImage)
                                        <div class="position-relative">
                                            <a href="{{ url('/') }}/{{ $attachment->file_path }}" target="_blank">
                                                <img src="{{ url('/') }}/{{ $attachment->file_path }}" 
                                                     alt="{{ $attachment->file_name }}"
                                                     class="img-thumbnail w-100" 
                                                     style="height: 150px; object-fit: cover; cursor: pointer;"
                                                     title="{{ $attachment->file_name }}">
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                                    style="top: 5px; right: 5px; padding: 4px 8px;"
                                                    onclick="deleteMainAttachment({{ $attachment->id }})"
                                                    title="Delete">
                                                <i class="ti ti-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <div class="mt-1">
                                                <small class="text-muted d-block text-truncate" title="{{ $attachment->file_name }}">
                                                    {{ $attachment->file_name }}
                                                </small>
                                                <small class="text-muted">{{ $attachment->user->name }} • {{ $attachment->created_at->format('M d') }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="ti ti-file text-primary" style="font-size: 2rem;"></i>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ url('/') }}/{{ $attachment->file_path }}" 
                                                           download="{{ $attachment->file_name }}"
                                                           class="text-decoration-none fw-semibold d-block text-truncate"
                                                           title="{{ $attachment->file_name }}">
                                                            {{ Str::limit($attachment->file_name, 25) }}
                                                        </a>
                                                        <small class="text-muted d-block">{{ $attachment->user->name }}</small>
                                                        <small class="text-muted">{{ $attachment->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                                            onclick="deleteMainAttachment({{ $attachment->id }})"
                                                            title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-4 bg-light rounded">
                                        <i class="ti ti-paperclip-off" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-2 mb-0">No attachments yet. Click "Upload Files" to add.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
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
                                <!-- Upload Button -->
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('show-ticket-attachments-input').click()">
                                        <i class="ti ti-upload me-1"></i>Upload Files
                                    </button>
                                    <input type="file" id="show-ticket-attachments-input" multiple 
                                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.xlsx,.xls,.zip"
                                           style="display: none;" 
                                           onchange="uploadShowTicketAttachments()">
                                    <small class="text-muted ms-2">Max 10MB per file. Multiple files allowed.</small>
                                </div>

                                <div class="row g-2">
                                @forelse($thisData->attachments as $attachment)
                                        @php
                                            $extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        <div class="col-md-{{ $isImage ? '4' : '6' }}">
                                            @if($isImage)
                                                <div class="position-relative">
                                                    <a href="{{ url('/') }}/{{ $attachment->file_path }}" target="_blank">
                                                        <img src="{{ url('/') }}/{{ $attachment->file_path }}" 
                                                             alt="{{ $attachment->file_name }}"
                                                             class="img-thumbnail w-100" 
                                                             style="height: 150px; object-fit: cover; cursor: pointer;"
                                                             title="{{ $attachment->file_name }}">
                                                    </a>
                                                    @if(auth()->id() == $attachment->user_id)
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                                                style="top: 5px; right: 5px; padding: 2px 6px;"
                                                                onclick="deleteShowAttachment({{ $attachment->id }})"
                                                                title="Delete">
                                                            <i class="ti ti-trash" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                    @endif
                                                    <small class="text-muted d-block mt-1 text-truncate">{{ $attachment->file_name }}</small>
                                                    <small class="text-muted d-block">{{ $attachment->user->name }} • {{ $attachment->created_at->format('M d') }}</small>
                                                </div>
                                            @else
                                                <div class="card">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="ti ti-file text-primary" style="font-size: 1.5rem;"></i>
                                                            <div class="flex-grow-1">
                                                                <a href="{{ url('/') }}/{{ $attachment->file_path }}" 
                                                                   download="{{ $attachment->file_name }}"
                                                                   class="text-decoration-none d-block text-truncate"
                                                                   title="{{ $attachment->file_name }}">
                                                                    {{ Str::limit($attachment->file_name, 30) }}
                                                                </a>
                                                                <small class="text-muted">{{ $attachment->user->name }} • {{ $attachment->created_at->format('M d, Y') }}</small>
                                        </div>
                                        @if(auth()->id() == $attachment->user_id)
                                                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                                                        onclick="deleteShowAttachment({{ $attachment->id }})"
                                                                        title="Delete">
                                                                    <i class="ti ti-trash"></i>
                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                        @endif
                                    </div>
                                @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="ti ti-paperclip-off" style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-2">No attachments yet. Click "Upload Files" to add attachments.</p>
                                            </div>
                                        </div>
                                @endforelse
                                </div>
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
<!-- Quill Rich Text Editor -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

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

// Quill editor instance
let showDescriptionQuill = null;

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

// Toggle Description Edit (Show Page)
document.getElementById('toggle-show-description-edit')?.addEventListener('click', function() {
    const viewDiv = document.getElementById('show-view-description');
    const editContainer = document.getElementById('show-edit-description-container');
    const currentDescription = viewDiv.innerHTML;
    
    viewDiv.style.display = 'none';
    editContainer.style.display = 'block';
    
    // Initialize Quill editor if not already initialized
    if (!showDescriptionQuill) {
        showDescriptionQuill = new Quill('#show-description-editor', {
            theme: 'snow',
            placeholder: 'Enter ticket description...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });
    }
    
    // Set current content
    if (currentDescription && currentDescription.trim() !== '' && !currentDescription.includes('No description provided')) {
        showDescriptionQuill.root.innerHTML = currentDescription;
    } else {
        showDescriptionQuill.setText('');
    }
    
    showDescriptionQuill.focus();
});

// Save Description (Show Page)
window.saveShowDescription = function() {
    if (!showDescriptionQuill) return;
    
    const htmlContent = showDescriptionQuill.root.innerHTML.trim();
    
    console.log('Saving show page description:', {htmlContent});
    quickUpdateField('description', htmlContent);
    
    // Update UI
    setTimeout(() => {
        document.getElementById('show-view-description').innerHTML = htmlContent || 'No description provided.';
        cancelShowDescriptionEdit();
    }, 500);
};

// Cancel Description Edit (Show Page)
window.cancelShowDescriptionEdit = function() {
    document.getElementById('show-view-description').style.display = 'block';
    document.getElementById('show-edit-description-container').style.display = 'none';
};

// Store original title (properly escaped)
const originalTicketTitle = {!! json_encode($thisData->title) !!};

// Save Title
window.saveTitle = function() {
    const newTitle = document.getElementById('edit_ticket_title_input').value.trim();
    if (newTitle === '' || newTitle === originalTicketTitle) {
        cancelTitleEdit();
        return;
    }
    quickUpdateField('title', newTitle);
    
    // Update UI
    setTimeout(() => {
        document.getElementById('view_ticket_title').textContent = newTitle;
        cancelTitleEdit();
    }, 500);
};

// Cancel Title Edit
window.cancelTitleEdit = function() {
    document.getElementById('view_ticket_title_container').style.display = 'block';
    document.getElementById('edit_ticket_title_container').style.display = 'none';
    document.getElementById('edit_ticket_title_input').value = originalTicketTitle;
};

// Initialize Title Edit functionality
function initTitleEdit() {
    console.log('Initializing title edit...');
    
    // Toggle Title Edit - Click handler
    const toggleTitleBtn = document.getElementById('toggle-title-edit');
    console.log('Toggle button found:', toggleTitleBtn);
    
    if (toggleTitleBtn) {
        toggleTitleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Title edit button clicked!');
            document.getElementById('view_ticket_title_container').style.display = 'none';
            document.getElementById('edit_ticket_title_container').style.display = 'block';
            document.getElementById('edit_ticket_title_input').focus();
            document.getElementById('edit_ticket_title_input').select();
        });
        console.log('Title edit click listener attached!');
    } else {
        console.error('Toggle title button not found!');
    }
    
    // Allow Escape key in title textarea (Enter creates new line, Ctrl+Enter saves)
    const titleInput = document.getElementById('edit_ticket_title_input');
    if (titleInput) {
        titleInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                saveTitle();
            }
            if (e.key === 'Escape') {
                e.preventDefault();
                cancelTitleEdit();
            }
        });
    }
}

// Run initialization - handles both cases (DOM ready or not)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTitleEdit);
} else {
    // DOM is already ready, run immediately
    initTitleEdit();
}

// Store original description (properly escaped) - used by rich text editor
const originalTicketDescription = {!! json_encode($thisData->description) !!};

// Quick field update function
function quickUpdateField(fieldName, fieldValue) {
    console.log('Quick updating:', fieldName, 'to:', fieldValue);
    
    // Fetch current ticket data first, then update with new value
    const cacheBuster = new Date().getTime();
    fetch(`/{{ getSystemPrefix() }}/projects/{{ $project->id }}/tickets/{{ $thisData->id }}/show?_=${cacheBuster}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.ticket) {
            const ticket = data.ticket;
            
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('project_id', {{ $project->id }});
            formData.append('title', fieldName === 'title' ? fieldValue : ticket.title);
            formData.append('description', fieldName === 'description' ? fieldValue : (ticket.description || ''));
            formData.append('ticket_status_id', fieldName === 'ticket_status_id' ? fieldValue : ticket.ticket_status_id);
            formData.append('priority', fieldName === 'priority' ? fieldValue : ticket.priority);
            formData.append('type', fieldName === 'type' ? fieldValue : ticket.type);
            formData.append('assignee_id', fieldName === 'assignee_id' ? (fieldValue || '') : (ticket.assignee_id || ''));
            formData.append('due_date', fieldName === 'due_date' ? (fieldValue || '') : (ticket.due_date || ''));
            formData.append('story_points', fieldName === 'story_points' ? (fieldValue || '') : (ticket.story_points || ''));
            formData.append('status', ticket.status);
            
            console.log('FormData for update:', {
                fieldName: fieldName,
                fieldValue: fieldValue,
                description: formData.get('description')
            });
            
            // Show loading toast
            showToast('info', 'Updating...');
            
            return fetch(`/{{ getSystemPrefix() }}/projects/{{ $project->id }}/tickets/{{ $thisData->id }}`, {
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
        if (response && (response.ok || response.redirected)) {
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
            formData.append('title', fieldName === 'title' ? fieldValue : ticket.title);
            formData.append('description', fieldName === 'description' ? fieldValue : (ticket.description || ''));
            
            // Debug: Log what we're sending
            console.log('FormData for update:', {
                fieldName: fieldName,
                fieldValue: fieldValue,
                title: formData.get('title'),
                description: formData.get('description')
            });
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

// Upload Ticket Attachments (Show Page)
window.uploadShowTicketAttachments = function() {
    const input = document.getElementById('show-ticket-attachments-input');
    
    if (!input.files || input.files.length === 0) {
        return;
    }
    
    const formData = new FormData();
    
    // Add all selected files
    Array.from(input.files).forEach(file => {
        formData.append('attachments[]', file);
    });
    formData.append('ticket_id', ticketId);
    
    showToast('info', `Uploading ${input.files.length} file(s)...`);
    
    fetch(`/{{ getSystemPrefix() }}/tickets/${ticketId}/upload-attachments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Files uploaded successfully!');
            input.value = '';
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message || 'Failed to upload files');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to upload files');
    });
};

// Delete Attachment (Show Page - Attachments Tab)
window.deleteShowAttachment = function(attachmentId) {
    if (!confirm('Delete this attachment permanently?')) {
        return;
    }
    
    showToast('info', 'Deleting attachment...');
    
    fetch(`/{{ getSystemPrefix() }}/ticket-attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Attachment deleted successfully!');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message || 'Failed to delete attachment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to delete attachment');
    });
};

// Upload Ticket Attachments (Main Section)
window.uploadMainTicketAttachments = function() {
    const input = document.getElementById('main-ticket-attachments-input');
    
    if (!input.files || input.files.length === 0) {
        return;
    }
    
    const formData = new FormData();
    
    // Add all selected files
    Array.from(input.files).forEach(file => {
        formData.append('attachments[]', file);
    });
    formData.append('ticket_id', ticketId);
    
    showToast('info', `Uploading ${input.files.length} file(s)...`);
    
    fetch(`/{{ getSystemPrefix() }}/tickets/${ticketId}/upload-attachments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Files uploaded successfully!');
            input.value = '';
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message || 'Failed to upload files');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to upload files');
    });
};

// Delete Attachment (Main Section)
window.deleteMainAttachment = function(attachmentId) {
    if (!confirm('Delete this attachment permanently?')) {
        return;
    }
    
    const attachmentElement = document.getElementById('attachment-' + attachmentId);
    if (attachmentElement) {
        attachmentElement.style.opacity = '0.5';
    }
    
    showToast('info', 'Deleting attachment...');
    
    fetch(`/{{ getSystemPrefix() }}/ticket-attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Attachment deleted successfully!');
            if (attachmentElement) {
                attachmentElement.remove();
            }
            setTimeout(() => location.reload(), 800);
        } else {
            showToast('error', data.message || 'Failed to delete attachment');
            if (attachmentElement) {
                attachmentElement.style.opacity = '1';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to delete attachment');
        if (attachmentElement) {
            attachmentElement.style.opacity = '1';
        }
    });
};

// Format mentions in comments to be clickable
function formatMentionsInComments() {
    const commentTexts = document.querySelectorAll('.comment-text');
    const users = @json($users ?? []); // Get users list
    
    commentTexts.forEach(commentElement => {
        let text = commentElement.textContent;
        if (!text) return;
        
        let result = text;
        const sortedUsers = [...users].sort((a, b) => b.name.length - a.name.length);
        const replacedPositions = new Set();
        
        sortedUsers.forEach(user => {
            // Try both formats: with spaces and without spaces
            const mentionFormats = [
                '@' + user.name,
                '@' + user.name.replace(/\s+/g, '')
            ];
            
            mentionFormats.forEach(mentionText => {
                let startIndex = 0;
                
                while ((startIndex = result.indexOf(mentionText, startIndex)) !== -1) {
                    if (!replacedPositions.has(startIndex)) {
                        const before = result.substring(0, startIndex);
                        const after = result.substring(startIndex + mentionText.length);
                        
                        const link = '<a href="/{{ getSystemPrefix() }}/users/' + user.id + 
                            '" target="_blank" class="badge bg-label-primary text-decoration-none"' +
                            ' title="' + user.email + '">' +
                            mentionText + '</a>';
                        
                        result = before + link + after;
                        replacedPositions.add(startIndex);
                        startIndex += link.length;
                    } else {
                        startIndex += mentionText.length;
                    }
                }
            });
        });
        
        if (result !== text) {
            commentElement.innerHTML = result;
        }
    });
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    formatMentionsInComments();
});

// Also run after any dynamic content is added (e.g., after adding a new comment)
window.formatMentionsInComments = formatMentionsInComments;
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

/* Quill List Display Fixes - ONLY for display mode, NOT in editor */
/* Fix bullet lists displayed as numbered lists - only outside Quill editor */
#show-view-description ol[data-list="bullet"]:not(.ql-editor ol),
#show-view-description ol li[data-list="bullet"]:not(.ql-editor li),
.comment-text ol[data-list="bullet"]:not(.ql-editor ol),
.comment-text ol li[data-list="bullet"]:not(.ql-editor li),
#view_ticket_description ol[data-list="bullet"]:not(.ql-editor ol),
#view_ticket_description ol li[data-list="bullet"]:not(.ql-editor li) {
    list-style-type: disc !important;
}

/* Ensure ordered lists display as numbers - only outside Quill editor */
#show-view-description ol[data-list="ordered"]:not(.ql-editor ol),
#show-view-description ol li[data-list="ordered"]:not(.ql-editor li),
.comment-text ol[data-list="ordered"]:not(.ql-editor ol),
.comment-text ol li[data-list="ordered"]:not(.ql-editor li),
#view_ticket_description ol[data-list="ordered"]:not(.ql-editor ol),
#view_ticket_description ol li[data-list="ordered"]:not(.ql-editor li) {
    list-style-type: decimal !important;
}

/* Hide Quill's internal UI elements in display mode ONLY (not in editor) */
#show-view-description:not(.ql-editor) .ql-ui,
.comment-text:not(.ql-editor) .ql-ui,
#view_ticket_description:not(.ql-editor) .ql-ui {
    display: none;
}

/* Style lists properly in description display - only in view mode */
#show-view-description:not(.ql-editor) ol,
#show-view-description:not(.ql-editor) ul,
.comment-text:not(.ql-editor) ol,
.comment-text:not(.ql-editor) ul,
#view_ticket_description:not(.ql-editor) ol,
#view_ticket_description:not(.ql-editor) ul {
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
}

#show-view-description:not(.ql-editor) ol li,
#show-view-description:not(.ql-editor) ul li,
.comment-text:not(.ql-editor) ol li,
.comment-text:not(.ql-editor) ul li,
#view_ticket_description:not(.ql-editor) ol li,
#view_ticket_description:not(.ql-editor) ul li {
    margin-bottom: 0.25rem;
}
</style>
@endsection
