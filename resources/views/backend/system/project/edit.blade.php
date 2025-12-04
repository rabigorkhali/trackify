@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('projects.update', $thisData->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label> *
                        <input required value="{{ $thisData->name }}" type="text" name="name" id="name"
                            class="form-control @if ($errors->first('name')) is-invalid @endif"
                            placeholder="Project Name" />
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    </div>

                    <!-- Key -->
                    <div class="col-md-6">
                        <label class="form-label" for="key">{{ __('Key') }}</label> *
                        <input required value="{{ $thisData->key }}" type="text" name="key" id="key"
                            maxlength="10" class="form-control @if ($errors->first('key')) is-invalid @endif"
                            placeholder="PROJ" style="text-transform: uppercase;" />
                        <small class="text-muted">Unique project key (max 10 characters, uppercase)</small>
                        <div class="invalid-feedback">{{ $errors->first('key') }}</div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @if ($errors->first('description')) is-invalid @endif" placeholder="Project Description">{{ $thisData->description }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                    </div>

                    <!-- Avatar -->
                    <div class="col-md-6">
                        <label class="form-label" for="avatar">{{ __('Avatar') }}</label>
                        <input type="file" name="avatar" id="avatar"
                            class="form-control @if ($errors->first('avatar')) is-invalid @endif" />
                        <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
                        @if ($thisData->avatar)
                            <div class="col-md-6 mt-2">
                                <a target="_blank" href="{{ asset($thisData->avatar) }}">
                                    <img src="{{ asset($thisData->avatar) }}" width="100" alt="Avatar"
                                        class="img-fluid">
                                </a>
                            </div>
                        @endif
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
                                    @foreach ($users as $user)
                                        @php
                                            $isMember = $thisData->members->where('id', $user->id)->first() !== null;
                                        @endphp
                                        <tr>
                                            <td>{{ $user->name }} ({{ $user->email }})</td>
                                            <td class="text-center">
                                                <input type="checkbox" name="members[]" value="{{ $user->id }}"
                                                    class="form-check-input" style="width: 20px; height: 20px;"
                                                    @if ($isMember) checked @endif>
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
                            <input id="status1" type="radio" name="status" value="1"
                                class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                @if ($thisData->status == 1) checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                @if ($thisData->status == 0) checked @endif>
                            <label for="status2" class="form-check-label">{{ __('Inactive') }}</label>
                        </div>
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Update') }}</button>
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
