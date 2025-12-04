@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Create Project') }}</h5>

            <form class="card-body" action="{{ route('projects.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label> *
                        <input required type="text" name="name" id="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" placeholder="Project Name" />
                        <div class="invalid-feedback">@error('name') {{ $message }} @enderror</div>
                    </div>

                    <!-- Key -->
                    <div class="col-md-6">
                        <label class="form-label" for="key">{{ __('Key') }}</label> *
                        <input required type="text" name="key" id="key" value="{{ old('key') }}" maxlength="10"
                               class="form-control @error('key') is-invalid @enderror" placeholder="PROJ" style="text-transform: uppercase;" />
                        <small class="text-muted">Unique project key (max 10 characters, uppercase)</small>
                        <div class="invalid-feedback">@error('key') {{ $message }} @enderror</div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Project Description">{{ old('description') }}</textarea>
                        <div class="invalid-feedback">@error('description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Avatar -->
                    <div class="col-md-6">
                        <label class="form-label" for="avatar">{{ __('Avatar') }}</label>
                        <input type="file" name="avatar" id="avatar"
                               class="form-control @error('avatar') is-invalid @enderror" />
                        <div class="invalid-feedback">@error('avatar') {{ $message }} @enderror</div>
                    </div>

                    <!-- Members -->
                    <div class="col-md-12">
                        <label class="form-label">{{ __('Project Members') }}</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th class="text-center" style="width: 100px;">Member</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }} ({{ $user->email }})</td>
                                            <td class="text-center">
                                                <input type="checkbox" name="members[]" value="{{ $user->id }}" class="form-check-input" style="width: 20px; height: 20px;">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input type="radio" id="status1" name="status" value="1" checked
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if(old('status') == '1') checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                   class="form-check-input @error('status') is-invalid @enderror"
                                   @if(old('status') == '0') checked @endif>
                            <label for="status2" class="form-check-label">{{ __('Inactive') }}</label>
                        </div>
                        <div class="invalid-feedback">@error('status') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Create') }}</button>
                </div>

            </form>
        </div>
    </div>
    <script>
        document.getElementById('key').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
@endsection

