@extends('backend.system.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('backend.system.partials.errors')
        <div class="card mb-4">
            <h5 class="card-header">{{ $title }}</h5>

            <form class="card-body" action="{{ route('tickets.update', [$project->id, $thisData->id]) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $thisData->id }}">
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
                        <input required value="{{ $thisData->title }}" type="text" name="title" id="title"
                               class="form-control @if ($errors->first('title')) is-invalid @endif"
                               placeholder="Ticket Title"/>
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label" for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="6"
                                  class="form-control text-editor @if ($errors->first('description')) is-invalid @endif"
                                  placeholder="Ticket Description">{{ $thisData->description }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label" for="ticket_status_id">{{ __('Status') }}</label> *
                        <select required class="form-control @if ($errors->first('ticket_status_id')) is-invalid @endif"
                                name="ticket_status_id">
                            <option value="">{{__('Select Status')}}</option>
                            @foreach($ticketStatuses as $status)
                                <option @if($thisData->ticket_status_id == $status->id) selected
                                        @endif value="{{ $status->id }}">{{ ucfirst($status->name) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('ticket_status_id') }}</div>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-6">
                        <label class="form-label" for="priority">{{ __('Priority') }}</label> *
                        <select required class="form-control @if ($errors->first('priority')) is-invalid @endif"
                                name="priority">
                            <option value="low" @if($thisData->priority == 'low') selected @endif>Low</option>
                            <option value="medium" @if($thisData->priority == 'medium') selected @endif>Medium</option>
                            <option value="high" @if($thisData->priority == 'high') selected @endif>High</option>
                            <option value="critical" @if($thisData->priority == 'critical') selected @endif>Critical</option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label" for="type">{{ __('Type') }}</label> *
                        <select required class="form-control @if ($errors->first('type')) is-invalid @endif"
                                name="type">
                            <option value="bug" @if($thisData->type == 'bug') selected @endif>Bug</option>
                            <option value="task" @if($thisData->type == 'task') selected @endif>Task</option>
                            <option value="story" @if($thisData->type == 'story') selected @endif>Story</option>
                            <option value="epic" @if($thisData->type == 'epic') selected @endif>Epic</option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                    </div>

                    <!-- Assignee -->
                    <div class="col-md-6">
                        <label class="form-label" for="assignee_id">{{ __('Assignee') }}</label>
                        <select class="form-control @if ($errors->first('assignee_id')) is-invalid @endif"
                                name="assignee_id">
                            <option value="">{{__('Unassigned')}}</option>
                            @foreach($users as $user)
                                <option @if($thisData->assignee_id == $user->id) selected
                                        @endif value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('assignee_id') }}</div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label class="form-label" for="due_date">{{ __('Due Date') }}</label>
                        <input type="date" name="due_date" id="due_date" value="{{ $thisData->due_date ? $thisData->due_date->format('Y-m-d') : '' }}"
                               class="form-control @if ($errors->first('due_date')) is-invalid @endif" />
                        <div class="invalid-feedback">{{ $errors->first('due_date') }}</div>
                    </div>

                    <!-- Story Points -->
                    <div class="col-md-6">
                        <label class="form-label" for="story_points">{{ __('Story Points') }}</label>
                        <input type="number" name="story_points" id="story_points" value="{{ $thisData->story_points }}"
                               class="form-control @if ($errors->first('story_points')) is-invalid @endif" min="0" max="100" />
                        <div class="invalid-feedback">{{ $errors->first('story_points') }}</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label w-100" for="status">{{ __('Status') }}</label>
                        <div class="form-check-inline">
                            <input id="status1" type="radio" name="status" value="1"
                                   class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                   @if($thisData->status == 1) checked @endif>
                            <label for="status1" class="form-check-label">{{ __('Active') }}</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="status2" name="status" value="0"
                                   class="form-check-input @if ($errors->first('status')) is-invalid @endif"
                                   @if($thisData->status == 0) checked @endif>
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
@endsection

