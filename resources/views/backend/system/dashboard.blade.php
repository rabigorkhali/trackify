@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl">
        @include('backend.system.partials.errors')
        <div class="row justify-content-center">
            <div class="col-md-2 mb-2">
                <a href="{{ route('generateSitemap') }}" class="btn btn-primary w-100">Generate Sitemap</a>
            </div>

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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            {{ __('Dashboard') }}
                            <div>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                sd {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Welcome!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
