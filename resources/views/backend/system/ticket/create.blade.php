@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Create Ticket') }}</h5>

            <form class="card-body" action="{{ route('tickets.store', $project->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="row g-3">
                    <!-- Project Info -->
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>{{ __('Project') }}:</strong> {{ $project->name }} ({{ $project->key }})
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="col-md-12">
                        <label class="form-label" for="title">{{ __('Title') }}</label> *
                        <input required type="text" name="title" id="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror" placeholder="Ticket Title" />
                        <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="6"
                                  class="form-control text-editor @error('description') is-invalid @enderror"
                                  placeholder="Ticket Description">{{ old('description') }}</textarea>
                        <div class="invalid-feedback">@error('description') {{ $message }} @enderror</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label" for="ticket_status_id">{{ __('Status') }}</label> *
                        <select required class="form-control @error('ticket_status_id') is-invalid @enderror" name="ticket_status_id">
                            <option value="">{{ __('Select Status') }}</option>
                            @foreach($ticketStatuses as $status)
                                <option @if(old('ticket_status_id') == $status->id) selected @endif value="{{ $status->id }}">
                                    {{ ucfirst($status->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">@error('ticket_status_id') {{ $message }} @enderror</div>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-6">
                        <label class="form-label" for="priority">{{ __('Priority') }}</label> *
                        <select required class="form-control @error('priority') is-invalid @enderror" name="priority">
                            <option value="low" @if(old('priority') == 'low') selected @endif>Low</option>
                            <option value="medium" @if(old('priority', 'medium') == 'medium') selected @endif>Medium</option>
                            <option value="high" @if(old('priority') == 'high') selected @endif>High</option>
                            <option value="critical" @if(old('priority') == 'critical') selected @endif>Critical</option>
                        </select>
                        <div class="invalid-feedback">@error('priority') {{ $message }} @enderror</div>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label" for="type">{{ __('Type') }}</label> *
                        <select required class="form-control @error('type') is-invalid @enderror" name="type">
                            <option value="bug" @if(old('type') == 'bug') selected @endif>Bug</option>
                            <option value="task" @if(old('type', 'task') == 'task') selected @endif>Task</option>
                            <option value="story" @if(old('type') == 'story') selected @endif>Story</option>
                            <option value="epic" @if(old('type') == 'epic') selected @endif>Epic</option>
                        </select>
                        <div class="invalid-feedback">@error('type') {{ $message }} @enderror</div>
                    </div>

                    <!-- Assignee -->
                    <div class="col-md-6">
                        <label class="form-label" for="assignee_id">{{ __('Assignee') }}</label>
                        <select class="form-control @error('assignee_id') is-invalid @enderror" name="assignee_id">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach($users as $user)
                                <option @if(old('assignee_id') == $user->id) selected @endif value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">@error('assignee_id') {{ $message }} @enderror</div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label class="form-label" for="due_date">{{ __('Due Date') }}</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                               class="form-control @error('due_date') is-invalid @enderror" />
                        <div class="invalid-feedback">@error('due_date') {{ $message }} @enderror</div>
                    </div>

                    <!-- Story Points -->
                    <div class="col-md-6">
                        <label class="form-label" for="story_points">{{ __('Story Points') }}</label>
                        <input type="number" name="story_points" id="story_points" value="{{ old('story_points') }}"
                               class="form-control @error('story_points') is-invalid @enderror" min="0" max="100" />
                        <div class="invalid-feedback">@error('story_points') {{ $message }} @enderror</div>
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

@endsection

