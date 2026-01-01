@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl">
        @include('backend.system.partials.errors')
        <div class="row justify-content-center">
            @if(hasPermission('/backup-database','get'))
                <div class="col-md-3 mb-2">
                    <a href="{{ route('backup.database') }}" class="btn btn-primary w-100">
                        Download Database Backup
                    </a>
                </div>
            @endif

            @if(hasPermission('/backup-project','get'))
                <div class="col-md-3 mb-2">
                    <a href="{{ route('backup.project') }}" class="btn btn-success w-100">
                        Download Project Backup
                    </a>
                </div>
            @endif
        </div>

    </div>
    <div class="container-xxl">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title text-white mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
                                <p class="card-text text-white-50 mb-0">Here's what's happening with your projects today.</p>
                            </div>
                            <div class="text-end">
                                <i class="fa fa-calendar fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            @if(hasPermission('/projects', 'get'))
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow h-100" style="border-left: 4px solid #5e72e4;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Projects</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalProjects ?? 0 }}</div>
                                <small class="text-muted">Active: {{ $activeProjects ?? 0 }}</small>
                            </div>
                            <div class="ms-3">
                                <i class="fa fa-folder fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/projects/*/tickets', 'get'))
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow h-100" style="border-left: 4px solid #11cdef;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Tickets</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalTickets ?? 0 }}</div>
                                <small class="text-muted">All projects</small>
                            </div>
                            <div class="ms-3">
                                <i class="fa fa-ticket-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/users', 'get'))
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow h-100" style="border-left: 4px solid #2dce89;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Users</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                                <small class="text-muted">Active: {{ $activeUsers ?? 0 }}</small>
                            </div>
                            <div class="ms-3">
                                <i class="fa fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/posts', 'get'))
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow h-100" style="border-left: 4px solid #fb6340;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Posts</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalPosts ?? 0 }}</div>
                                <small class="text-muted">Content items</small>
                            </div>
                            <div class="ms-3">
                                <i class="fa fa-file-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketTrends))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Ticket Trends (Last 7 Days)</h6>
                    </div>
                    <div class="card-body">
                        <div id="ticketTrendsChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByPriority))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tickets by Priority</h6>
                    </div>
                    <div class="card-body">
                        <div id="ticketsByPriorityChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row mb-4">
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByStatus))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tickets by Status</h6>
                    </div>
                    <div class="card-body">
                        <div id="ticketsByStatusChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByType))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tickets by Type</h6>
                    </div>
                    <div class="card-body">
                        <div id="ticketsByTypeChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Projects and Tickets Row -->
        <div class="row mb-4">
            @if(hasPermission('/projects', 'get') && isset($projectsWithStats))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Top Projects</h6>
                        @if(hasPermission('/projects', 'post'))
                        <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> New Project
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($projectsWithStats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th class="text-end">Tickets</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projectsWithStats as $project)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($project->avatar)
                                                        <img src="{{ asset('uploads/' . $project->avatar) }}" 
                                                             alt="{{ $project->name }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px; font-size: 14px;">
                                                            {{ strtoupper(substr($project->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $project->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $project->key }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-info">{{ $project->tickets_count }}</span>
                                            </td>
                                            <td class="text-end">
                                                @if(hasPermission('/projects/*/tickets', 'get'))
                                                <a href="{{ route('tickets.index', $project->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-4">No projects found.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('/projects/*/tickets', 'get') && isset($recentTickets))
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Tickets</h6>
                    </div>
                    <div class="card-body">
                        @if($recentTickets->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentTickets as $ticket)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('tickets.show', [$ticket->project_id, $ticket->id]) }}" 
                                                   class="text-decoration-none">
                                                    {{ $ticket->ticket_key }}: {{ $ticket->title }}
                                                </a>
                                            </h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="fa fa-folder"></i> {{ $ticket->project->name ?? 'N/A' }}
                                                @if($ticket->assignee)
                                                    | <i class="fa fa-user"></i> {{ $ticket->assignee->name }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            @if($ticket->ticketStatus)
                                                <span class="badge" 
                                                      style="background-color: {{ $ticket->ticketStatus->color ?? '#6c757d' }}">
                                                    {{ $ticket->ticketStatus->name }}
                                                </span>
                                            @endif
                                            <br>
                                            <small class="text-muted">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-4">No recent tickets.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
                    </div>

        <!-- Additional Content Statistics -->
        @if(hasPermission('/events', 'get') || hasPermission('/testimonials', 'get') || hasPermission('/teams', 'get') || hasPermission('/contact-us', 'get'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Content Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(hasPermission('/events', 'get'))
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fa fa-calendar-alt fa-2x text-primary me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $totalEvents ?? 0 }}</h5>
                                        <small class="text-muted">Events</small>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(hasPermission('/testimonials', 'get'))
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fa fa-star fa-2x text-warning me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $totalTestimonials ?? 0 }}</h5>
                                        <small class="text-muted">Testimonials</small>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(hasPermission('/teams', 'get'))
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fa fa-users fa-2x text-success me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $totalTeams ?? 0 }}</h5>
                                        <small class="text-muted">Team Members</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                            @if(hasPermission('/contact-us', 'get'))
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fa fa-envelope fa-2x text-info me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $totalContactUs ?? 0 }}</h5>
                                        <small class="text-muted">Contact Messages</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ticket Trends Chart
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketTrends))
            var ticketTrendsOptions = {
                series: [{
                    name: 'Tickets Created',
                    data: [{{ implode(',', array_values($ticketTrends)) }}]
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth' },
                xaxis: {
                    categories: [@foreach(array_keys($ticketTrends) as $date)'{{ date('M d', strtotime($date)) }}',@endforeach]
                },
                colors: ['#5e72e4'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3
                    }
                }
            };
            var ticketTrendsChart = new ApexCharts(document.querySelector("#ticketTrendsChart"), ticketTrendsOptions);
            ticketTrendsChart.render();
            @endif

            // Tickets by Priority Chart
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByPriority))
            var priorityData = @json($ticketsByPriority);
            var priorityLabels = Object.keys(priorityData);
            var priorityValues = Object.values(priorityData);
            var priorityColors = {
                'low': '#28a745',
                'medium': '#ffc107',
                'high': '#fd7e14',
                'critical': '#dc3545'
            };
            
            var ticketsByPriorityOptions = {
                series: priorityValues,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: priorityLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                colors: priorityLabels.map(label => priorityColors[label.toLowerCase()] || '#6c757d'),
                legend: { position: 'bottom' }
            };
            var ticketsByPriorityChart = new ApexCharts(document.querySelector("#ticketsByPriorityChart"), ticketsByPriorityOptions);
            ticketsByPriorityChart.render();
            @endif

            // Tickets by Status Chart
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByStatus))
            var statusData = @json($ticketsByStatus);
            var statusLabels = Object.keys(statusData);
            var statusValues = Object.values(statusData);
            
            var ticketsByStatusOptions = {
                series: [{
                    name: 'Tickets',
                    data: statusValues
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: statusLabels
                },
                colors: ['#5e72e4']
            };
            var ticketsByStatusChart = new ApexCharts(document.querySelector("#ticketsByStatusChart"), ticketsByStatusOptions);
            ticketsByStatusChart.render();
            @endif

            // Tickets by Type Chart
            @if(hasPermission('/projects/*/tickets', 'get') && isset($ticketsByType))
            var typeData = @json($ticketsByType);
            var typeLabels = Object.keys(typeData);
            var typeValues = Object.values(typeData);
            var typeColors = {
                'bug': '#dc3545',
                'task': '#007bff',
                'story': '#28a745',
                'epic': '#6f42c1'
            };
            
            var ticketsByTypeOptions = {
                series: typeValues,
                chart: {
                    type: 'pie',
                    height: 300
                },
                labels: typeLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                colors: typeLabels.map(label => typeColors[label.toLowerCase()] || '#6c757d'),
                legend: { position: 'bottom' }
            };
            var ticketsByTypeChart = new ApexCharts(document.querySelector("#ticketsByTypeChart"), ticketsByTypeOptions);
            ticketsByTypeChart.render();
            @endif
        });
    </script>
@endsection
@endsection
