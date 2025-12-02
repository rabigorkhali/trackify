@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">Activity Logs</h5>
            <div class="card">
                <div class="card-datatable table-responsive">
                    <form method="get" action="{{ route('activities.index') }}">
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
                                                <input type="text" value="{{ request('keyword') }}" name="keyword" class="form-control" placeholder="Search..">
                                            </label>
                                        </div>
                                        <div class="dt-buttons btn-group flex-wrap">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-primary mx-3">
                                                    <span><i class="ti ti-filter me-1 ti-xs"></i> Filter</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="datatables-pages table dataTable no-footer dtr-column" id="DataTables_Table_0">
                                <thead class="border-top">
                                <tr>
                                    <th>{{ __('SN') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Log Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Route') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                    <th>{{ __('User Agent') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                @if (!$thisDatas->count())
                                    <tr>
                                        <td class="text-center" colspan="9">{{ __('No activity logs found.') }}</td>
                                    </tr>
                                @endif
                                @foreach ($thisDatas as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $log->causer?->name ?? 'Guest' }}</td>
                                        <td>{{ $log->log_name }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->getExtraProperty('route') ?? 'N/A' }}</td>
                                        <td>{{ $log->getExtraProperty('method') ?? 'N/A' }}</td>
                                        <td>{{ $log->getExtraProperty('ip') ?? 'N/A' }}</td>
                                        <td>{{ $log->getExtraProperty('user_agent') ?? 'N/A' }}</td>
                                        <td>{{ $log->created_at->format('d M Y, H:i A') }}</td>
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
