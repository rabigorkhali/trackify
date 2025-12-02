@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $title }}</span>
                <div>
                    <a href="{{ route('tickets.kanban', $project->id) }}" class="btn btn-label-primary btn-sm">
                        <i class="ti ti-layout-kanban me-1"></i>Kanban View
                    </a>
                    @if(hasPermission('/'.str_replace(' ','-',strtolower($title)),'post'))
                        <a class="btn add-new btn-primary text-white btn-sm" href="{{ route('tickets.create', $project->id) }}">
                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                            <span class="d-none d-sm-inline-block">Add</span>
                        </a>
                    @endif
                </div>
            </h5>
            <div class="card">
                <div class="card-datatable table-responsive">
                    <form method="get" action="{{ route('tickets.index', $project->id) }}">
                        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row me-2">
                                <div class="col-md-2">
                                    <div class="me-3">
                                        <div class="dataTables_length">
                                            <label>
                                                <select name="show" class="form-select">
                                                    <option value="10" @if (request('show') == 10) selected @endif>10</option>
                                                    <option value="25" @if (request('show') == 25) selected @endif>25</option>
                                                    <option value="50" @if (request('show') == 50) selected @endif>50</option>
                                                    <option value="100" @if (request('show') == 100) selected @endif>100</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                        <div class="dataTables_filter">
                                            <label>
                                                <input type="text" value="{{ request('keyword') }}" name="keyword" class="form-control" placeholder="Search...">
                                            </label>
                                        </div>
                                        <div class="dt-buttons btn-group flex-wrap">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-primary mx-3">
                                                    <span><i class="ti ti-filter me-1 ti-xs"></i>Filter</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="datatables table dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                <thead class="border-top">
                                <tr>
                                    <th>{{ __('SN') }}</th>
                                    <th>{{ __('Key') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Assignee') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                @if (!$thisDatas->count())
                                    <tr>
                                        <td class="text-center" colspan="7">{{ __('No data found.') }}</td>
                                    </tr>
                                @endif
                                @foreach ($thisDatas as $ticketKey => $ticket)
                                    <tr>
                                        <td>{{ $ticketKey + 1 }}</td>
                                        <td><span class="badge bg-label-info">{{ $ticket->ticket_key }}</span></td>
                                        <td>{{ $ticket->title }}</td>
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
                                                    'critical' => 'bg-label-danger'
                                                ];
                                            @endphp
                                            <span class="badge {{ $priorityColors[$ticket->priority] ?? 'bg-label-secondary' }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->assignee->name ?? 'Unassigned' }}</td>
                                        <td>
                                            @if(hasPermission('/' . str_replace(' ','-',strtolower($title)) . '/*', 'put') || hasPermission('/' . str_replace(' ','-',strtolower($title)) . '/*', 'delete'))
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('tickets.show', [$project->id, $ticket->id]) }}"><i class="ti ti-eye me-1"></i>{{ __('View') }}</a>
                                                        @if(hasPermission('/' . str_replace(' ','-',strtolower($title)) . '/*', 'put'))
                                                            <a class="dropdown-item" href="{{ route('tickets.edit', [$project->id, $ticket->id]) }}"><i class="ti ti-pencil me-1"></i>{{ __('Edit') }}</a>
                                                        @endif
                                                        @if(hasPermission('/' . str_replace(' ','-',strtolower($title)) . '/*', 'delete'))
                                                            <a href="#" class="dropdown-item delete-button" data-bs-toggle="modal" data-actionurl="{{ route('tickets.destroy', [$project->id, $ticket->id]) }}" data-bs-target="#deleteModal">
                                                                <i class="ti ti-trash me-1"></i>{{ __('Delete') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row mx-2">
                                <div class="row">
                                    <div class="col">
                                        <nav>
                                            {{ $thisDatas->appends(request()->query())->links('pagination::bootstrap-4') }}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

