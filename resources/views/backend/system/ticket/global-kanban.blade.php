@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <span class="mb-2 mb-md-0"><i class="ti ti-layout-board me-2"></i>{{ $title }}</span>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('ticket-labels.index') }}" class="btn btn-label-info btn-sm">
                        <i class="ti ti-tag me-1"></i>Manage Labels
                    </a>
                </div>
            </h5>
            <div class="card-body">
                <!-- Stats Info -->
                <div class="alert alert-primary mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <strong><i class="ti ti-ticket me-1"></i>{{ __('Total Tickets') }}:</strong> 
                            {{ $ticketStatuses->sum(function($s) { return $s->tickets->count(); }) }}
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge bg-info">{{ $projects->count() }} Projects</span>
                            <span class="badge bg-success ms-2">{{ $users->count() }} Users</span>
                        </div>
                    </div>
                </div>

                <!-- Comprehensive Filters -->
                <form method="get" action="{{ route('kanban.index') }}" class="mb-4">
                    <div class="row g-3">
                        <!-- Project Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-folder me-1"></i>{{ __('Project') }}</label>
                            <select name="project_id" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __('All Projects') }}</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" @if(request('project_id') == $project->id) selected @endif>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Assignee Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-user me-1"></i>{{ __('Assignee') }}</label>
                            <select name="assignee_id" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __('All Assignees') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(request('assignee_id') == $user->id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Priority Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-alert-circle me-1"></i>{{ __('Priority') }}</label>
                            <select name="priority" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __('All') }}</option>
                                <option value="low" @if(request('priority') == 'low') selected @endif>Low</option>
                                <option value="medium" @if(request('priority') == 'medium') selected @endif>Medium</option>
                                <option value="high" @if(request('priority') == 'high') selected @endif>High</option>
                                <option value="critical" @if(request('priority') == 'critical') selected @endif>Critical</option>
                            </select>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-category me-1"></i>{{ __('Type') }}</label>
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="bug" @if(request('type') == 'bug') selected @endif>Bug</option>
                                <option value="task" @if(request('type') == 'task') selected @endif>Task</option>
                                <option value="story" @if(request('type') == 'story') selected @endif>Story</option>
                                <option value="epic" @if(request('type') == 'epic') selected @endif>Epic</option>
                            </select>
                        </div>
                        
                        <!-- Due Date Range Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-calendar me-1"></i>{{ __('Due Date From') }}</label>
                            <input type="date" name="due_date_from" class="form-control" value="{{ request('due_date_from') }}" onchange="this.form.submit()">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-calendar me-1"></i>{{ __('Due Date To') }}</label>
                            <input type="date" name="due_date_to" class="form-control" value="{{ request('due_date_to') }}" onchange="this.form.submit()">
                        </div>
                        
                        <!-- Clear Filters Button -->
                        <div class="col-md-12">
                            <button type="button" class="btn btn-label-secondary" onclick="window.location.href='{{ route('kanban.index') }}'">
                                <i class="ti ti-refresh me-1"></i>Clear All Filters
                            </button>
                            <span class="ms-3 text-muted small">
                                @if(request()->query())
                                    <i class="ti ti-filter me-1"></i>Filters active
                                @endif
                            </span>
                        </div>
                    </div>
                </form>

                <!-- Kanban Board -->
                <div class="kanban-container" style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 20px;">
                    @foreach($ticketStatuses as $status)
                        <div class="kanban-column" style="min-width: 350px; background: #f8f9fa; border-radius: 10px; padding: 15px;">
                            <div class="kanban-header" style="margin-bottom: 15px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge" style="background-color: {{ $status->color }}; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem;">
                                            {{ $status->name }}
                                        </span>
                                        @if($status->name == 'To Do' || $status->order == 1)
                                            <button type="button" class="btn btn-sm btn-icon p-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#createTicketModal"
                                                    data-status-id="{{ $status->id }}"
                                                    title="Quick create ticket"
                                                    style="width: 24px; height: 24px; border-radius: 50%; background: {{ $status->color }}; border: none;">
                                                <i class="ti ti-plus text-white" style="font-size: 0.9rem;"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <span class="badge bg-label-secondary" id="count-{{ $status->id }}" style="font-size: 0.85rem;">{{ $status->tickets->count() }}</span>
                                </div>
                            </div>
                            <div class="kanban-list" data-status-id="{{ $status->id }}" style="min-height: 200px;">
                                @foreach($status->tickets as $ticket)
                                    <div class="kanban-item global-ticket-card" 
                                         data-ticket-id="{{ $ticket->id }}"
                                         data-project-id="{{ $ticket->project_id }}"
                                         style="background: white; border-radius: 8px; padding: 14px; margin-bottom: 12px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.08); border-left: 4px solid {{ 
                                            $ticket->priority == 'critical' ? '#ea5455' : 
                                            ($ticket->priority == 'high' ? '#ff9f43' : 
                                            ($ticket->priority == 'medium' ? '#28c76f' : '#00cfe8'))
                                         }};"
                                         data-draggable="true"
                                         onclick="openTicketModal({{ $ticket->id }}, {{ $ticket->project_id }})">
                                        
                                        <!-- Header with Quick Edit -->
                                        <div class="d-none">
                                            <button class="edit-ticket-btn" data-ticket-id="{{ $ticket->id }}" data-project-id="{{ $ticket->project_id }}"></button>
                                        </div>
                                        
                                        <!-- Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="badge bg-label-primary" style="font-size: 0.7rem;">{{ $ticket->ticket_key }}</span>
                                                <!-- Project Badge -->
                                                <span class="badge bg-label-info" style="font-size: 0.65rem;" title="{{ $ticket->project->name }}">
                                                    <i class="ti ti-folder"></i> {{ Str::limit($ticket->project->name, 15) }}
                                                </span>
                                                @php
                                                    $typeIcons = [
                                                        'bug' => 'ti-bug',
                                                        'task' => 'ti-checkbox',
                                                        'story' => 'ti-book',
                                                        'epic' => 'ti-rocket'
                                                    ];
                                                @endphp
                                                <i class="ti {{ $typeIcons[$ticket->type] ?? 'ti-checkbox' }}" style="font-size: 0.9rem; color: #697a8d;"></i>
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <h6 class="mb-2" style="font-size: 0.9rem; font-weight: 600; line-height: 1.4;">{{ Str::limit($ticket->title, 60) }}</h6>

                                        <!-- Labels -->
                                        @if($ticket->labels && $ticket->labels->count() > 0)
                                            <div class="mb-2 d-flex flex-wrap gap-1">
                                                @foreach($ticket->labels->take(3) as $label)
                                                    <span class="badge" style="background-color: {{ $label->color }}; font-size: 0.65rem; padding: 3px 8px;">
                                                        {{ $label->name }}
                                                    </span>
                                                @endforeach
                                                @if($ticket->labels->count() > 3)
                                                    <span class="badge bg-label-secondary" style="font-size: 0.65rem; padding: 3px 8px;">
                                                        +{{ $ticket->labels->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Metadata -->
                                        <div class="d-flex justify-content-between align-items-center mt-2" style="font-size: 0.75rem; color: #697a8d;">
                                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                                <!-- Priority Badge -->
                                                <span class="badge @php
                                                    $priorityColors = [
                                                        'low' => 'bg-label-success',
                                                        'medium' => 'bg-label-warning',
                                                        'high' => 'bg-label-danger',
                                                        'critical' => 'bg-danger'
                                                    ];
                                                @endphp {{ $priorityColors[$ticket->priority] ?? 'bg-label-secondary' }}" style="font-size: 0.65rem;">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>

                                                <!-- Comments Count -->
                                                @if($ticket->comments && $ticket->comments->count() > 0)
                                                    <span title="{{ $ticket->comments->count() }} comments">
                                                        <i class="ti ti-message"></i> {{ $ticket->comments->count() }}
                                                    </span>
                                                @endif

                                                <!-- Attachments Count -->
                                                @if($ticket->attachments && $ticket->attachments->count() > 0)
                                                    <span title="{{ $ticket->attachments->count() }} attachments">
                                                        <i class="ti ti-paperclip"></i> {{ $ticket->attachments->count() }}
                                                    </span>
                                                @endif

                                                <!-- Checklist Progress -->
                                                @if($ticket->checklists && $ticket->checklists->count() > 0)
                                                    @php
                                                        $completedCount = $ticket->checklists->where('is_completed', true)->count();
                                                        $totalCount = $ticket->checklists->count();
                                                        $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                                    @endphp
                                                    <span title="Checklist {{ $completedCount }}/{{ $totalCount }}" class="{{ $percentage == 100 ? 'text-success' : '' }}">
                                                        <i class="ti ti-checkbox"></i> {{ $completedCount }}/{{ $totalCount }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Quick Assignee Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0 d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; background: transparent;" onclick="event.stopPropagation();">
                                                    @if($ticket->assignee)
                                                        <div class="avatar avatar-xs">
                                                            @if($ticket->assignee->image)
                                                                <img src="{{ asset($ticket->assignee->image) }}" alt="{{ $ticket->assignee->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                                                            @else
                                                                <span class="avatar-initial rounded-circle" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}; font-size: 0.65rem;">
                                                                    {{ strtoupper(substr($ticket->assignee->name, 0, 3)) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <i class="ti ti-user-plus" style="font-size: 1.2rem; color: #697a8d;"></i>
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><h6 class="dropdown-header">Assign to</h6></li>
                                                    <li><a class="dropdown-item quick-assign" href="javascript:void(0);" data-ticket-id="{{ $ticket->id }}" data-project-id="{{ $ticket->project_id }}" data-assignee-id="">
                                                        <i class="ti ti-user-x me-2"></i>Unassigned
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @foreach($users as $user)
                                                        <li><a class="dropdown-item quick-assign {{ $ticket->assignee_id == $user->id ? 'active' : '' }}" href="javascript:void(0);" data-ticket-id="{{ $ticket->id }}" data-project-id="{{ $ticket->project_id }}" data-assignee-id="{{ $user->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-xs me-2">
                                                                    @if($user->image)
                                                                        <img src="{{ asset($user->image) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                                                                    @else
                                                                        <span class="avatar-initial rounded-circle" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}; font-size: 0.65rem;">
                                                                            {{ strtoupper(substr($user->name, 0, 3)) }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <span>{{ $user->name }}</span>
                                                            </div>
                                                        </a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Due Date -->
                                        @if($ticket->due_date)
                                            <div class="mt-2">
                                                @php
                                                    $isOverdue = $ticket->due_date->isPast();
                                                    $isDueSoon = !$isOverdue && $ticket->due_date->diffInDays(now()) <= 3;
                                                @endphp
                                                <span class="badge {{ $isOverdue ? 'bg-danger' : ($isDueSoon ? 'bg-warning' : 'bg-label-info') }}" style="font-size: 0.65rem;">
                                                    <i class="ti ti-calendar"></i> 
                                                    {{ $ticket->due_date->format('M d, Y') }}
                                                    @if($isOverdue)
                                                        (Overdue)
                                                    @elseif($isDueSoon)
                                                        (Due Soon)
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- View/Edit Ticket Modal -->
    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-label-primary">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <span class="badge bg-primary" id="view_ticket_key">TICKET-1</span>
                        <h5 class="modal-title mb-0 flex-grow-1" id="view_ticket_title">Ticket Title</h5>
                        <span class="badge bg-label-info" id="view_project_name">Project</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Main Content Area -->
                        <div class="col-md-8 border-end">
                            <div class="p-4">
                                <!-- Description -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><i class="ti ti-file-text me-1"></i>Description</h6>
                                        <button type="button" class="btn btn-sm btn-icon" id="toggle-description-edit" title="Edit description">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                    <div id="view_ticket_description" class="p-3 bg-light rounded text-muted" style="white-space: pre-wrap; min-height: 80px; display: block;">No description provided</div>
                                    <div id="edit_ticket_description_container" style="display: none;">
                                        <div id="description-editor" style="min-height: 200px;"></div>
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="saveDescription()">
                                                <i class="ti ti-check me-1"></i>Save
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cancelDescriptionEdit()">
                                                <i class="ti ti-x me-1"></i>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Attachments -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><i class="ti ti-paperclip me-1"></i>Ticket Attachments <span class="badge bg-label-secondary" id="attachments_count">0</span></h6>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('ticket_attachments_input').click()">
                                            <i class="ti ti-upload me-1"></i>Upload
                                        </button>
                                    </div>
                                    <input type="file" id="ticket_attachments_input" multiple 
                                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.xlsx,.xls,.zip"
                                           style="display: none;" 
                                           onchange="uploadTicketAttachments()">
                                    <div id="ticket_attachments_list">
                                        <!-- Attachments will be loaded here -->
                                    </div>
                                </div>

                                <!-- Comments Section -->
                                <div class="mb-4">
                                    <h6 class="mb-3"><i class="ti ti-message me-1"></i>Comments <span class="badge bg-label-secondary" id="comments_count">0</span></h6>
                                    
                                    <!-- Add Comment Form -->
                                    <div class="mb-3">
                                        <form id="addCommentForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" id="comment_ticket_id" name="ticket_id">
                                            <textarea class="form-control mb-2" id="new_comment" name="comment" rows="3" placeholder="Add a comment... (Type @ to mention users)"></textarea>
                                            
                                            <!-- File Upload (Multiple) -->
                                            <div class="mb-2">
                                                <label for="comment_attachments" class="form-label small">
                                                    <i class="ti ti-paperclip me-1"></i>Attachments (optional)
                                                </label>
                                                <input type="file" class="form-control form-control-sm" id="comment_attachments" name="attachments[]" 
                                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.xlsx,.xls" multiple>
                                                <div class="form-text">Max 10MB per file. Multiple files allowed.</div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="ti ti-send me-1"></i>Add Comment
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Comments List -->
                                    <div id="comments_list">
                                        <!-- Comments will be loaded here -->
                                    </div>
                                </div>

                                <!-- Activity Log -->
                                <div class="mb-4">
                                    <h6 class="mb-3"><i class="ti ti-history me-1"></i>Activity</h6>
                                    <div id="activity_list" style="max-height: 300px; overflow-y: auto;">
                                        <!-- Activities will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <div class="p-4">
                                <form id="quickUpdateForm">
                                    @csrf
                                    <input type="hidden" id="edit_ticket_id" name="id">
                                    <input type="hidden" id="edit_project_id" name="project_id">
                                    <input type="hidden" name="_method" value="PUT">
                                    
                                    <div class="row g-3">
                                        <!-- Status -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_ticket_status_id"><i class="ti ti-flag me-1"></i>Status</label>
                                            <select class="form-control quick-update-field" name="ticket_status_id" id="edit_ticket_status_id" data-field="ticket_status_id">
                                                <option value="">Select Status</option>
                                                @foreach($ticketStatuses as $status)
                                                    <option value="{{ $status->id }}">{{ ucfirst($status->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Priority -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_priority"><i class="ti ti-alert-circle me-1"></i>Priority</label>
                                            <select class="form-control quick-update-field" name="priority" id="edit_priority" data-field="priority">
                                                <option value="low">🟢 Low</option>
                                                <option value="medium">🟡 Medium</option>
                                                <option value="high">🟠 High</option>
                                                <option value="critical">🔴 Critical</option>
                                            </select>
                                        </div>

                                        <!-- Type -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_type"><i class="ti ti-category me-1"></i>Type</label>
                                            <select class="form-control quick-update-field" name="type" id="edit_type" data-field="type">
                                                <option value="bug">🐛 Bug</option>
                                                <option value="task">✅ Task</option>
                                                <option value="story">📖 Story</option>
                                                <option value="epic">🚀 Epic</option>
                                            </select>
                                        </div>

                                        <!-- Assignee -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_assignee_id"><i class="ti ti-user me-1"></i>Assignee</label>
                                            <select class="form-control quick-update-field" name="assignee_id" id="edit_assignee_id" data-field="assignee_id">
                                                <option value="">Unassigned</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Due Date -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_due_date"><i class="ti ti-calendar me-1"></i>Due Date</label>
                                            <input type="date" name="due_date" id="edit_due_date" class="form-control quick-update-field" data-field="due_date" />
                                        </div>

                                        <!-- Story Points -->
                                        <div class="col-12">
                                            <label class="form-label" for="edit_story_points"><i class="ti ti-star me-1"></i>Story Points</label>
                                            <input type="number" name="story_points" id="edit_story_points" class="form-control quick-update-field" data-field="story_points" min="0" max="100" />
                                        </div>

                                        <!-- Divider -->
                                        <div class="col-12">
                                            <hr class="my-2">
                                        </div>

                                        <!-- Project -->
                                        <div class="col-12">
                                            <label class="form-label"><i class="ti ti-folder me-1"></i>Project</label>
                                            <div id="view_project_link" class="text-muted">-</div>
                                        </div>

                                        <!-- Reporter -->
                                        <div class="col-12">
                                            <label class="form-label"><i class="ti ti-user-check me-1"></i>Reporter</label>
                                            <div id="view_reporter" class="text-muted">
                                                <a href="#" id="reporter_link" target="_blank" class="text-decoration-none text-dark">-</a>
                                            </div>
                                        </div>

                                        <!-- Created Date -->
                                        <div class="col-12">
                                            <label class="form-label"><i class="ti ti-clock me-1"></i>Created</label>
                                            <div id="view_created_at" class="text-muted">-</div>
                                        </div>

                                        <!-- Updated Date -->
                                        <div class="col-12">
                                            <label class="form-label"><i class="ti ti-clock-edit me-1"></i>Updated</label>
                                            <div id="view_updated_at" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-label-primary">
                    <h5 class="modal-title"><i class="ti ti-plus me-2"></i>{{ __('Create New Ticket') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createTicketForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="max-height: calc(100vh - 250px); overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Project -->
                            <div class="col-md-12">
                                <label class="form-label" for="create_project_id"><i class="ti ti-folder me-1"></i>{{ __('Project') }}</label> *
                                <select required class="form-control" name="project_id" id="create_project_id">
                                    <option value="">{{ __('Select Project') }}</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->key }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title -->
                            <div class="col-md-12">
                                <label class="form-label" for="create_title"><i class="ti ti-text-size me-1"></i>{{ __('Title') }}</label> *
                                <input required type="text" name="title" id="create_title" class="form-control" placeholder="Enter ticket title" />
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label class="form-label" for="create_description"><i class="ti ti-file-text me-1"></i>{{ __('Description') }}</label>
                                <textarea name="description" id="create_description" rows="4" class="form-control" placeholder="Enter ticket description"></textarea>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_ticket_status_id"><i class="ti ti-flag me-1"></i>{{ __('Status') }}</label> *
                                <select required class="form-control" name="ticket_status_id" id="create_ticket_status_id">
                                    @foreach($ticketStatuses as $status)
                                        <option value="{{ $status->id }}">{{ ucfirst($status->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_priority"><i class="ti ti-alert-circle me-1"></i>{{ __('Priority') }}</label> *
                                <select required class="form-control" name="priority" id="create_priority">
                                    <option value="low">🟢 Low</option>
                                    <option value="medium" selected>🟡 Medium</option>
                                    <option value="high">🟠 High</option>
                                    <option value="critical">🔴 Critical</option>
                                </select>
                            </div>

                            <!-- Type -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_type"><i class="ti ti-category me-1"></i>{{ __('Type') }}</label> *
                                <select required class="form-control" name="type" id="create_type">
                                    <option value="bug">🐛 Bug</option>
                                    <option value="task" selected>✅ Task</option>
                                    <option value="story">📖 Story</option>
                                    <option value="epic">🚀 Epic</option>
                                </select>
                            </div>

                            <!-- Assignee -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_assignee_id"><i class="ti ti-user me-1"></i>{{ __('Assignee') }}</label>
                                <select class="form-control" name="assignee_id" id="create_assignee_id">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_due_date"><i class="ti ti-calendar me-1"></i>{{ __('Due Date') }}</label>
                                <input type="date" name="due_date" id="create_due_date" class="form-control" />
                            </div>

                            <!-- Story Points -->
                            <div class="col-md-6">
                                <label class="form-label" for="create_story_points"><i class="ti ti-star me-1"></i>{{ __('Story Points') }}</label>
                                <input type="number" name="story_points" id="create_story_points" class="form-control" min="0" max="100" />
                            </div>

                            <!-- Active Status -->
                            <input type="hidden" name="status" value="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>{{ __('Create Ticket') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tributejs@5.1.3/dist/tribute.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tributejs@5.1.3/dist/tribute.css">
<!-- Quill Rich Text Editor -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
@php
    $userListData = $users->map(function($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image
        ];
    })->values();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kanbanLists = document.querySelectorAll('.kanban-list');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    // Users list for mentions
    const usersList = @json($userListData);
    
    // Initialize Tribute.js for @mentions
    let tribute = null;
    
    // Quill editor instance for description
    let descriptionQuill = null;
    
    function initializeMentions() {
        const commentTextarea = document.getElementById('new_comment');
        if (!commentTextarea || tribute) return;
        
        tribute = new Tribute({
            values: usersList,
            lookup: 'name',
            fillAttr: 'name',
            selectTemplate: function(item) {
                return '@' + item.original.name;
            },
            menuItemTemplate: function(item) {
                return '<div class="d-flex align-items-center gap-2">' +
                    '<div class="avatar avatar-xs">' +
                    '<span class="avatar-initial rounded-circle bg-label-primary">' +
                    item.original.name.substring(0, 2).toUpperCase() +
                    '</span>' +
                    '</div>' +
                    '<div>' +
                    '<div class="fw-semibold">' + item.original.name + '</div>' +
                    '<small class="text-muted">' + item.original.email + '</small>' +
                    '</div>' +
                    '</div>';
            },
            noMatchTemplate: function() {
                return '<span class="text-muted">No users found</span>';
            }
        });
        
        tribute.attach(commentTextarea);
    }
    
    // Extract mentioned user IDs from comment text
    function extractMentions(text) {
        const mentions = [];
        const mentionRegex = /@(\w+(?:\s+\w+)*)/g;
        let match;
        
        while ((match = mentionRegex.exec(text)) !== null) {
            const mentionedName = match[1];
            const user = usersList.find(u => u.name.toLowerCase() === mentionedName.toLowerCase());
            if (user) {
                mentions.push(user.id);
            }
        }
        
        return mentions;
    }
    
    // Handle Create Ticket Form
    const createTicketForm = document.getElementById('createTicketForm');
    if (createTicketForm) {
        createTicketForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const projectId = document.getElementById('create_project_id').value;
            
            if (!projectId) {
                showNotification('Please select a project', 'error');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Creating...';
            
            const formData = new FormData(this);
            
            fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (response.redirected || response.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createTicketModal'));
                    modal.hide();
                    showNotification('Ticket created successfully!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.errors) {
                    showNotification('Validation errors occurred', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-check me-1"></i>Create Ticket';
                } else if (data) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createTicketModal'));
                    modal.hide();
                    showNotification('Ticket created successfully!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-check me-1"></i>Create Ticket';
                showNotification('Failed to create ticket', 'error');
            });
        });
    }
    
    // Set default status when opening modal from column header
    document.querySelectorAll('[data-bs-target="#createTicketModal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const statusId = this.getAttribute('data-status-id');
            if (statusId) {
                setTimeout(() => {
                    document.getElementById('create_ticket_status_id').value = statusId;
                }, 100);
            }
        });
    });

    // Initialize Sortable for Kanban
    kanbanLists.forEach(function(list) {
        Sortable.create(list, {
            group: 'kanban-tickets',
            animation: 200,
            easing: "cubic-bezier(0.4, 0, 0.2, 1)",
            ghostClass: 'kanban-ghost',
            chosenClass: 'kanban-chosen',
            dragClass: 'kanban-drag',
            forceFallback: true,
            handle: '.kanban-item[data-draggable="true"]',
            onEnd: function(evt) {
                const ticketId = evt.item.getAttribute('data-ticket-id');
                const newStatusId = evt.to.getAttribute('data-status-id');
                const oldStatusId = evt.from.getAttribute('data-status-id');

                if (newStatusId === oldStatusId) {
                    return;
                }

                evt.item.style.opacity = '0.6';

                // Update ticket status
                fetch('{{ route("tickets.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ticket_id: ticketId,
                        status_id: newStatusId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    evt.item.style.opacity = '1';
                    if (data.success) {
                        // Update counts
                        const oldCount = document.getElementById('count-' + oldStatusId);
                        const newCount = document.getElementById('count-' + newStatusId);
                        if (oldCount) {
                            oldCount.textContent = parseInt(oldCount.textContent) - 1;
                        }
                        if (newCount) {
                            newCount.textContent = parseInt(newCount.textContent) + 1;
                        }
                        showNotification('Ticket status updated successfully', 'success');
                    } else {
                        evt.from.appendChild(evt.item);
                        showNotification(data.message || 'Failed to update ticket status', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    evt.item.style.opacity = '1';
                    evt.from.appendChild(evt.item);
                    showNotification('An error occurred while updating the ticket', 'error');
                });
            }
        });
    });

    // Notification function
    function showNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'info' ? 'alert-info' : 'alert-danger');
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Global function to open ticket modal with full details
    window.openTicketModal = function(ticketId, projectId) {
        loadTicketDetails(ticketId, projectId);
    };

    // Load full ticket details
    function loadTicketDetails(ticketId, projectId) {
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets/${ticketId}/show`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.ticket) {
                const ticket = data.ticket;
                
                // Populate header
                document.getElementById('view_ticket_key').textContent = ticket.ticket_key || 'N/A';
                document.getElementById('view_ticket_title').textContent = ticket.title || 'Untitled';
                document.getElementById('view_project_name').textContent = ticket.project?.name || 'N/A';
                
                // Populate description
                const descriptionText = ticket.description || 'No description provided';
                document.getElementById('view_ticket_description').textContent = descriptionText;
                
                // Store description for later use
                window.currentTicketDescription = ticket.description || '';
                
                // Load ticket attachments
                displayTicketAttachments(ticket.attachments || []);
                
                // Populate form fields
                document.getElementById('edit_ticket_id').value = ticket.id;
                document.getElementById('edit_project_id').value = projectId;
                document.getElementById('comment_ticket_id').value = ticket.id;
                document.getElementById('edit_ticket_status_id').value = ticket.ticket_status_id || '';
                document.getElementById('edit_priority').value = ticket.priority || 'medium';
                document.getElementById('edit_type').value = ticket.type || 'task';
                document.getElementById('edit_assignee_id').value = ticket.assignee_id || '';
                document.getElementById('edit_due_date').value = ticket.due_date || '';
                document.getElementById('edit_story_points').value = ticket.story_points || '';
                
                // Display project, reporter, created, updated
                const projectLink = document.getElementById('view_project_link');
                if (ticket.project) {
                    projectLink.innerHTML = `<a href="/{{ getSystemPrefix() }}/projects/${ticket.project.id}" target="_blank" class="text-decoration-none text-dark">${ticket.project.name}</a>`;
                } else {
                    projectLink.textContent = 'Unknown';
                }
                
                const reporterLink = document.getElementById('reporter_link');
                if (ticket.reporter) {
                    reporterLink.textContent = ticket.reporter.name;
                    reporterLink.href = `/{{ getSystemPrefix() }}/users/${ticket.reporter.id}`;
                } else {
                    reporterLink.textContent = 'Unknown';
                    reporterLink.href = '#';
                    reporterLink.style.pointerEvents = 'none';
                }
                document.getElementById('view_created_at').textContent = ticket.created_at || 'N/A';
                document.getElementById('view_updated_at').textContent = ticket.updated_at || 'N/A';
                
                // Load comments
                displayComments(ticket.comments || []);
                
                // Load activities
                displayActivities(ticket.activities || []);
                
                // Initialize mentions after modal content is ready
                setTimeout(() => {
                    initializeMentions();
                }, 100);
                
                // Open modal
                const modal = new bootstrap.Modal(document.getElementById('editTicketModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load ticket data', 'error');
        });
    }

    // Format mentions in comment text with links
    function formatMentions(text) {
        if (!text) return text;
        
        let result = text;
        const sortedUsers = [...usersList].sort((a, b) => b.name.length - a.name.length);
        const replacedPositions = new Set();
        
        sortedUsers.forEach(user => {
            const mentionText = '@' + user.name;
            let startIndex = 0;
            
            while ((startIndex = result.indexOf(mentionText, startIndex)) !== -1) {
                if (!replacedPositions.has(startIndex)) {
                    const before = result.substring(0, startIndex);
                    const after = result.substring(startIndex + mentionText.length);
                    
                    const link = '<a href="/{{ getSystemPrefix() }}/users/' + user.id + 
                                '" target="_blank" class="badge bg-label-primary text-decoration-none"' +
                                ' title="' + user.email + '" onclick="event.stopPropagation();">' + 
                                mentionText + '</a>';
                    
                    result = before + link + after;
                    replacedPositions.add(startIndex);
                    startIndex += link.length;
                } else {
                    startIndex += mentionText.length;
                }
            }
        });
        
        return result;
    }
    
    // Display comments
    function displayComments(comments) {
        const commentsList = document.getElementById('comments_list');
        const commentsCount = document.getElementById('comments_count');
        const currentUserId = {{ auth()->id() }};
        
        commentsCount.textContent = comments.length;
        
        if (comments.length === 0) {
            commentsList.innerHTML = '<div class="text-muted small">No comments yet. Be the first to comment!</div>';
            return;
        }
        
        let html = '';
        comments.forEach(comment => {
            const userProfileUrl = `/{{ getSystemPrefix() }}/users/${comment.user.id}`;
            const canDelete = currentUserId === comment.user.id;
            
            html += `
                <div class="mb-3 pb-3 border-bottom comment-item-${comment.id}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <a href="${userProfileUrl}" target="_blank" class="text-decoration-none">
                                <div class="avatar avatar-xs">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        ${comment.user.name.substring(0, 2).toUpperCase()}
                                    </span>
                                </div>
                            </a>
                            <div>
                                <a href="${userProfileUrl}" target="_blank" class="text-decoration-none">
                                    <strong class="small text-dark">${comment.user.name}</strong>
                                </a>
                                <div class="text-muted" style="font-size: 0.75rem;">${new Date(comment.created_at).toLocaleString()}</div>
                            </div>
                        </div>
                        ${canDelete ? `
                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                    onclick="deleteComment(${comment.id}, ${comment.ticket_id})"
                                    title="Delete comment">
                                <i class="ti ti-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                    <div class="ms-4 ps-2">
                        <p class="mb-2" style="white-space: pre-wrap;">${formatMentions(comment.comment)}</p>
                        ${comment.attachments && comment.attachments.length > 0 ? `
                            <div class="mt-2">
                                <small class="text-muted d-block mb-2">
                                    <i class="ti ti-paperclip me-1"></i>Attachments (${comment.attachments.length})
                                </small>
                                <div class="d-flex flex-wrap gap-2">
                                    ${comment.attachments.map(attachment => {
                                        const isImage = attachment.type && attachment.type.startsWith('image/');
                                        const fileName = attachment.name || attachment.path.split('/').pop();
                                        
                                        if (isImage) {
                                            return `
                                                <div class="position-relative" style="width: 100px; height: 100px;">
                                                    <a href="/${attachment.path}" target="_blank">
                                                        <img src="/${attachment.path}" alt="${fileName}" 
                                                             class="img-thumbnail" 
                                                             style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                                             title="${fileName}">
                                                    </a>
                                                </div>
                                            `;
                                        } else {
                                            return `
                                                <a href="/${attachment.path}" download="${fileName}" class="btn btn-sm btn-label-secondary d-flex align-items-center gap-1" title="Download ${fileName}">
                                                    <i class="ti ti-download"></i>
                                                    <span>${fileName.length > 15 ? fileName.substring(0, 15) + '...' : fileName}</span>
                                                </a>
                                            `;
                                        }
                                    }).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        
        commentsList.innerHTML = html;
    }

    // Toggle Description Edit Mode
    document.body.addEventListener('click', function(e) {
        if (e.target.id === 'toggle-description-edit' || e.target.closest('#toggle-description-edit')) {
            const viewDiv = document.getElementById('view_ticket_description');
            const editContainer = document.getElementById('edit_ticket_description_container');
            const currentDescription = window.currentTicketDescription || '';
            
            viewDiv.style.display = 'none';
            editContainer.style.display = 'block';
            
            // Initialize Quill editor if not already initialized
            if (!descriptionQuill) {
                descriptionQuill = new Quill('#description-editor', {
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
            if (currentDescription && currentDescription.trim() !== '') {
                descriptionQuill.root.innerHTML = currentDescription.replace(/\n/g, '<br>');
            } else {
                descriptionQuill.setText('');
            }
            
            descriptionQuill.focus();
        }
    });

    // Save Description
    window.saveDescription = function() {
        if (!descriptionQuill) return;
        
        const ticketId = document.getElementById('edit_ticket_id').value;
        const projectId = document.getElementById('edit_project_id').value;
        const htmlContent = descriptionQuill.root.innerHTML.trim();
        
        // Convert HTML to plain text for backend
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlContent;
        const plainText = tempDiv.innerText || tempDiv.textContent || '';
        
        updateTicketField(ticketId, projectId, 'description', plainText);
        
        // Update UI
        setTimeout(() => {
            document.getElementById('view_ticket_description').innerHTML = htmlContent || 'No description provided';
            window.currentTicketDescription = plainText;
            cancelDescriptionEdit();
        }, 500);
    };

    // Cancel Description Edit
    window.cancelDescriptionEdit = function() {
        document.getElementById('view_ticket_description').style.display = 'block';
        document.getElementById('edit_ticket_description_container').style.display = 'none';
    };

    // Display Ticket Attachments
    function displayTicketAttachments(attachments) {
        const attachmentsList = document.getElementById('ticket_attachments_list');
        const attachmentsCount = document.getElementById('attachments_count');
        
        attachmentsCount.textContent = attachments.length;
        
        if (attachments.length === 0) {
            attachmentsList.innerHTML = '<div class="text-muted small text-center py-3">No attachments yet. Click Upload to add files.</div>';
            return;
        }
        
        let html = '<div class="row g-2">';
        attachments.forEach(attachment => {
            const fileName = attachment.file_name || 'file';
            const filePath = attachment.file_path || '';
            const extension = fileName.split('.').pop()?.toLowerCase() || '';
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
            
            if (isImage) {
                html += `
                    <div class="col-md-4">
                        <div class="position-relative">
                            <a href="/${filePath}" target="_blank">
                                <img src="/${filePath}" alt="${fileName}" 
                                     class="img-thumbnail w-100" 
                                     style="height: 120px; object-fit: cover; cursor: pointer;"
                                     title="${fileName}">
                            </a>
                            <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                    style="top: 5px; right: 5px; padding: 2px 6px;"
                                    onclick="deleteTicketAttachment(${attachment.id})"
                                    title="Delete ${fileName}">
                                <i class="ti ti-trash" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1 text-truncate" title="${fileName}">${fileName}</small>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                            <i class="ti ti-file text-primary"></i>
                            <a href="/${filePath}" download="${fileName}" class="text-truncate flex-grow-1" title="${fileName}">
                                ${fileName.length > 30 ? fileName.substring(0, 30) + '...' : fileName}
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger" 
                                    onclick="deleteTicketAttachment(${attachment.id})"
                                    title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }
        });
        html += '</div>';
        
        attachmentsList.innerHTML = html;
    }

    // Upload Ticket Attachments
    window.uploadTicketAttachments = function() {
        const input = document.getElementById('ticket_attachments_input');
        const ticketId = document.getElementById('edit_ticket_id').value;
        const projectId = document.getElementById('edit_project_id').value;
        
        if (!input.files || input.files.length === 0) {
            return;
        }
        
        const formData = new FormData();
        
        // Add all selected files
        Array.from(input.files).forEach(file => {
            formData.append('attachments[]', file);
        });
        formData.append('ticket_id', ticketId);
        
        showNotification(`Uploading ${input.files.length} file(s)...`, 'info');
        
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/tickets/${ticketId}/upload-attachments`, {
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
                showNotification('Files uploaded successfully', 'success');
                input.value = '';
                // Reload ticket details
                loadTicketDetails(ticketId, projectId);
            } else {
                showNotification(data.message || 'Failed to upload files', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to upload files. Make sure route exists.', 'error');
        });
    };

    // Delete Ticket Attachment
    window.deleteTicketAttachment = function(attachmentId) {
        if (!confirm('Delete this attachment permanently?')) {
            return;
        }
        
        const ticketId = document.getElementById('edit_ticket_id').value;
        const projectId = document.getElementById('edit_project_id').value;
        
        showNotification('Deleting attachment...', 'info');
        
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/ticket-attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Attachment deleted successfully', 'success');
                loadTicketDetails(ticketId, projectId);
            } else {
                showNotification(data.message || 'Failed to delete attachment', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete attachment', 'error');
        });
    };

    // Delete Comment
    window.deleteComment = function(commentId, ticketId) {
        if (!confirm('Are you sure you want to delete this comment? All attachments will also be deleted.')) {
            return;
        }
        
        const commentElement = document.querySelector(`.comment-item-${commentId}`);
        if (commentElement) {
            commentElement.style.opacity = '0.5';
        }
        
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/ticket-comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message) {
                showNotification('Comment deleted successfully', 'success');
                // Remove comment from DOM
                if (commentElement) {
                    commentElement.remove();
                }
                // Update count
                const commentsCount = document.getElementById('comments_count');
                const currentCount = parseInt(commentsCount.textContent);
                commentsCount.textContent = Math.max(0, currentCount - 1);
                
                // Reload comments to get fresh data
                const projectId = document.getElementById('edit_project_id').value;
                setTimeout(() => loadComments(ticketId, projectId), 500);
            } else {
                showNotification('Failed to delete comment', 'error');
                if (commentElement) {
                    commentElement.style.opacity = '1';
                }
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            showNotification('Error deleting comment', 'error');
            if (commentElement) {
                commentElement.style.opacity = '1';
            }
        });
    };

    // Display activities
    function displayActivities(activities) {
        const activityList = document.getElementById('activity_list');
        
        if (activities.length === 0) {
            activityList.innerHTML = '<div class="text-muted small">No activity yet</div>';
            return;
        }
        
        let html = '';
        activities.forEach(activity => {
            const iconMap = {
                'created': 'ti-plus',
                'status_changed': 'ti-refresh',
                'assignee_changed': 'ti-user',
                'label_added': 'ti-tag',
                'label_removed': 'ti-tag-off',
                'comment_added': 'ti-message',
                'user_mentioned': 'ti-at'
            };
            
            const icon = iconMap[activity.action] || 'ti-point';
            const userName = activity.user ? activity.user.name : 'System';
            const userProfileUrl = activity.user ? `/{{ getSystemPrefix() }}/users/${activity.user.id}` : '#';
            
            html += `
                <div class="mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ti ${icon}" style="font-size: 0.9rem; margin-top: 2px;"></i>
                        <div class="flex-grow-1">
                            <div class="small">
                                ${activity.user ? 
                                    `<a href="${userProfileUrl}" target="_blank" class="text-decoration-none"><strong class="text-dark">${userName}</strong></a>` : 
                                    `<strong>${userName}</strong>`
                                } ${activity.description}
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">${new Date(activity.created_at).toLocaleString()}</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        activityList.innerHTML = html;
    }

    // Reload comments
    function loadComments(ticketId, projectId) {
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets/${ticketId}/show`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.ticket) {
                displayComments(data.ticket.comments || []);
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
    }

    // Reload activities
    function loadActivities(ticketId, projectId) {
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets/${ticketId}/show`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.ticket) {
                displayActivities(data.ticket.activities || []);
            }
        })
        .catch(error => {
            console.error('Error loading activities:', error);
        });
    }

    // Handle Add Comment
    document.getElementById('addCommentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const ticketId = document.getElementById('comment_ticket_id').value;
        const projectId = document.getElementById('edit_project_id').value;
        const comment = document.getElementById('new_comment').value.trim();
        const submitBtn = this.querySelector('button[type="submit"]');
        
        if (!comment) {
            showNotification('Please enter a comment', 'error');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Posting...';
        
        const formData = new FormData(this);
        const mentionedUserIds = extractMentions(comment);
        if (mentionedUserIds.length > 0) {
            formData.append('mentioned_users', JSON.stringify(mentionedUserIds));
        }
        
        fetch('{{ route("ticket-comments.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-1"></i>Add Comment';
            
            if (data.success) {
                showNotification('Comment added successfully', 'success');
                document.getElementById('new_comment').value = '';
                document.getElementById('comment_attachments').value = '';
                loadComments(ticketId, projectId);
                loadActivities(ticketId, projectId);
            } else {
                showNotification(data.message || 'Failed to add comment', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-1"></i>Add Comment';
            showNotification('Failed to add comment', 'error');
        });
    });

    // Handle Quick Updates (when field changes)
    document.querySelectorAll('.quick-update-field').forEach(function(field) {
        field.addEventListener('change', function() {
            const ticketId = document.getElementById('edit_ticket_id').value;
            const projectId = document.getElementById('edit_project_id').value;
            const fieldName = this.getAttribute('data-field');
            const fieldValue = this.value;
            
            updateTicketField(ticketId, projectId, fieldName, fieldValue);
        });
    });

    // Update single ticket field
    function updateTicketField(ticketId, projectId, fieldName, fieldValue) {
        showNotification('Updating...', 'info');
        
        fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets/${ticketId}/show`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.ticket) {
                const ticket = data.ticket;
                
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('project_id', projectId);
                formData.append('title', ticket.title);
                formData.append('description', ticket.description || '');
                formData.append('ticket_status_id', fieldName === 'ticket_status_id' ? fieldValue : ticket.ticket_status_id);
                formData.append('priority', fieldName === 'priority' ? fieldValue : ticket.priority);
                formData.append('type', fieldName === 'type' ? fieldValue : ticket.type);
                formData.append('assignee_id', fieldName === 'assignee_id' ? (fieldValue || '') : (ticket.assignee_id || ''));
                formData.append('due_date', fieldName === 'due_date' ? (fieldValue || '') : (ticket.due_date || ''));
                formData.append('story_points', fieldName === 'story_points' ? (fieldValue || '') : (ticket.story_points || ''));
                formData.append('status', ticket.status);
                
                return fetch(`{{ url('') }}/{{ getSystemPrefix() }}/projects/${projectId}/tickets/${ticketId}`, {
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
            if (response && response.redirected) {
                showNotification('Updated successfully', 'success');
                loadActivities(ticketId, projectId);
                return null;
            }
            return response ? response.json() : null;
        })
        .then(data => {
            if (data) {
                showNotification('Updated successfully', 'success');
                loadActivities(ticketId, projectId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update', 'error');
        });
    }

    // Update card assignee display without reload
    function updateCardAssignee(ticketCard, assigneeId) {
        if (!assigneeId) {
            // Show unassigned icon
            const avatarContainer = ticketCard.querySelector('.dropdown button');
            if (avatarContainer) {
                avatarContainer.innerHTML = '<i class="ti ti-user-plus" style="font-size: 1.2rem; color: #697a8d;"></i>';
            }
        } else {
            // Find user and update avatar
            const user = usersList.find(u => u.id == assigneeId);
            if (user) {
                const avatarContainer = ticketCard.querySelector('.dropdown button');
                if (avatarContainer) {
                    const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
                    if (user.image) {
                        const imageUrl = user.image.startsWith('http') ? user.image : `{{ url('') }}/${user.image}`;
                        avatarContainer.innerHTML = `
                            <div class="avatar avatar-xs">
                                <img src="${imageUrl}" alt="${user.name}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                            </div>
                        `;
                    } else {
                        avatarContainer.innerHTML = `
                            <div class="avatar avatar-xs">
                                <span class="avatar-initial rounded-circle" style="background-color: ${randomColor}; font-size: 0.65rem;">
                                    ${user.name.substring(0, 3).toUpperCase()}
                                </span>
                            </div>
                        `;
                    }
                }
            }
        }
    }

    // Handle Quick Assign
    document.body.addEventListener('click', function(e) {
        const assignBtn = e.target.classList.contains('quick-assign') ? e.target : e.target.closest('.quick-assign');
        
        if (assignBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            const ticketId = assignBtn.getAttribute('data-ticket-id');
            const projectId = assignBtn.getAttribute('data-project-id');
            const assigneeId = assignBtn.getAttribute('data-assignee-id') || null;
            const ticketCard = document.querySelector(`.kanban-item[data-ticket-id="${ticketId}"]`);
            
            console.log('Quick assign clicked:', { ticketId, projectId, assigneeId });
            
            const dropdown = assignBtn.closest('.dropdown');
            if (dropdown) {
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                if (dropdownMenu) dropdownMenu.classList.remove('show');
                const dropdownButton = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                if (dropdownButton) {
                    dropdownButton.classList.remove('show');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            }
            
            if (ticketCard) {
                ticketCard.style.opacity = '0.6';
            }
            
            fetch('{{ route("tickets.update-assignee") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ticket_id: ticketId,
                    assignee_id: assigneeId
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (ticketCard) {
                    ticketCard.style.opacity = '1';
                }
                if (data.success) {
                    showNotification('Assignee updated successfully', 'success');
                    // Update the assignee avatar in the card without reloading
                    const newAssigneeId = assigneeId;
                    updateCardAssignee(ticketCard, newAssigneeId);
                } else {
                    showNotification(data.message || 'Failed to update assignee', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (ticketCard) {
                    ticketCard.style.opacity = '1';
                }
                showNotification('Failed to update assignee', 'error');
            });
        }
    }, true);
});
</script>
<style>
/* Kanban Board Styling */
.kanban-ghost {
    opacity: 0.3;
    background: #f0f0f0;
}
.kanban-chosen {
    cursor: grabbing !important;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2) !important;
    transform: rotate(3deg);
}
.kanban-drag {
    opacity: 0.9;
    transform: rotate(2deg);
}
.kanban-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.kanban-item:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.15) !important;
    transform: translateY(-2px);
}
.kanban-item.sortable-drag {
    cursor: grabbing !important;
}
.kanban-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}
.kanban-container::-webkit-scrollbar {
    height: 10px;
}
.kanban-container::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 8px;
}
.kanban-container::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 8px;
}
.kanban-container::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
.kanban-column {
    transition: background 0.2s;
}
.kanban-column:hover {
    background: #f0f0f4 !important;
}
@keyframes badgePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
.badge-pulse {
    animation: badgePulse 0.3s ease-in-out;
}
.global-ticket-card .badge {
    font-size: 0.7rem;
}
</style>
@endsection

