@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')

        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="ti ti-report-analytics me-2"></i>{{ $title }}</span>
                @if(hasPermission('/reports/export', 'get'))
                    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="ti ti-download me-1"></i>Export CSV
                    </a>
                @endif
            </h5>
            <div class="card-body">
                <form method="get" action="{{ route('reports.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-user me-1"></i>{{ __('User') }}</label>
                            <select name="user_id" class="form-select">
                                <option value="">{{ __('All Users') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-user-check me-1"></i>{{ __('As') }}</label>
                            <select name="user_role" class="form-select">
                                <option value="assignee" @selected(request('user_role', 'assignee') == 'assignee')>Assignee</option>
                                <option value="reporter" @selected(request('user_role') == 'reporter')>Reporter</option>
                                <option value="both" @selected(request('user_role') == 'both')>Assignee or Reporter</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="ti ti-folder me-1"></i>{{ __('Project') }}</label>
                            <select name="project_id" class="form-select">
                                <option value="">{{ __('All Projects') }}</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-list-check me-1"></i>{{ __('Status') }}</label>
                            <select name="ticket_status_id" class="form-select">
                                <option value="">{{ __('All Statuses') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}" @selected(request('ticket_status_id') == $status->id)>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-category me-1"></i>{{ __('Type') }}</label>
                            <select name="type" class="form-select">
                                <option value="bug" @selected(request('type', 'bug') == 'bug')>Bug</option>
                                <option value="" @selected(request()->has('type') && request('type') === '')>All Types</option>
                                <option value="task" @selected(request('type') == 'task')>Task</option>
                                <option value="story" @selected(request('type') == 'story')>Story</option>
                                <option value="epic" @selected(request('type') == 'epic')>Epic</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-alert-circle me-1"></i>{{ __('Priority') }}</label>
                            <select name="priority" class="form-select">
                                <option value="">{{ __('All') }}</option>
                                <option value="low" @selected(request('priority') == 'low')>Low</option>
                                <option value="medium" @selected(request('priority') == 'medium')>Medium</option>
                                <option value="high" @selected(request('priority') == 'high')>High</option>
                                <option value="critical" @selected(request('priority') == 'critical')>Critical</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-calendar me-1"></i>{{ __('From Date') }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="ti ti-calendar me-1"></i>{{ __('To Date') }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter me-1"></i>Generate Report
                            </button>
                            <a href="{{ route('reports.index') }}" class="btn btn-label-secondary">
                                <i class="ti ti-refresh me-1"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>

                @if ($selectedUser)
                    <div class="alert alert-primary mb-4">
                        <strong><i class="ti ti-user me-1"></i>{{ $selectedUser->name }}</strong>
                        <span class="text-muted ms-2">{{ $selectedUser->email }}</span>
                        <span class="badge bg-info ms-2">{{ $tickets->count() }} {{ request('type', 'bug') ?: 'ticket' }}(s)</span>
                    </div>
                @endif

                <!-- Status Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card shadow-none border h-100" style="border-left: 4px solid #5e72e4 !important;">
                            <div class="card-body py-3">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total</div>
                                <div class="h4 mb-0 fw-bold">{{ $tickets->count() }}</div>
                            </div>
                        </div>
                    </div>
                    @foreach ($summaryByStatus as $item)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card shadow-none border h-100" style="border-left: 4px solid {{ $item['color'] }} !important;">
                                <div class="card-body py-3">
                                    <div class="text-xs fw-bold text-uppercase mb-1" style="color: {{ $item['color'] }}">
                                        {{ $item['name'] }}
                                    </div>
                                    <div class="h4 mb-0 fw-bold">{{ $item['count'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Project-wise Status Breakdown -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ti ti-folder me-1"></i>Status by Project</h6>
                    </div>
                    <div class="card-body table-responsive">
                        @if (count($summaryByProject) === 0)
                            <p class="text-muted mb-0 text-center py-3">No data found for the selected filters.</p>
                        @else
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Project') }}</th>
                                        @foreach ($statuses as $status)
                                            <th class="text-center">
                                                <span class="badge" style="background-color: {{ $status->color }}">{{ $status->name }}</span>
                                            </th>
                                        @endforeach
                                        <th class="text-center">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($summaryByProject as $row)
                                        <tr>
                                            <td>
                                                <strong>{{ $row['project_name'] }}</strong>
                                                @if ($row['project_key'])
                                                    <span class="badge bg-label-secondary ms-1">{{ $row['project_key'] }}</span>
                                                @endif
                                            </td>
                                            @foreach ($statuses as $status)
                                                <td class="text-center">
                                                    {{ $row['status_counts'][$status->id] ?? 0 }}
                                                </td>
                                            @endforeach
                                            <td class="text-center fw-bold">{{ $row['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th>{{ __('Total') }}</th>
                                        @foreach ($statuses as $status)
                                            <th class="text-center">
                                                {{ collect($summaryByProject)->sum(fn ($r) => $r['status_counts'][$status->id] ?? 0) }}
                                            </th>
                                        @endforeach
                                        <th class="text-center">{{ $tickets->count() }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- Detailed Ticket List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ti ti-list-details me-1"></i>Ticket Details</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="border-top">
                                <tr>
                                    <th>{{ __('SN') }}</th>
                                    <th>{{ __('Key') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Assignee') }}</th>
                                    <th>{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $index => $ticket)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('tickets.show', [$ticket->project_id, $ticket->id]) }}" class="badge bg-label-info text-decoration-none">
                                                {{ $ticket->ticket_key }}
                                            </a>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($ticket->title, 50) }}</td>
                                        <td>{{ $ticket->project->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $ticket->ticketStatus->color ?? '#6c757d' }}">
                                                {{ $ticket->ticketStatus->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-label-success',
                                                    'medium' => 'bg-label-warning',
                                                    'high' => 'bg-label-danger',
                                                    'critical' => 'bg-label-danger',
                                                ];
                                            @endphp
                                            <span class="badge {{ $priorityColors[$ticket->priority] ?? 'bg-label-secondary' }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->assignee->name ?? 'Unassigned' }}</td>
                                        <td>{{ optional($ticket->created_at)->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">{{ __('No tickets found for the selected filters.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
