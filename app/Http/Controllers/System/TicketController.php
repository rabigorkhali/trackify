<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\NotificationService;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends ResourceController
{
    protected $notificationService;

    public function __construct(private readonly TicketService $thisService, NotificationService $notificationService)
    {
        parent::__construct($thisService);
        $this->notificationService = $notificationService;
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\TicketRequest';
    }

    public function updateValidationRequest()
    {
        return 'App\Http\Requests\System\TicketRequest';
    }

    public function isSubModule()
    {
        return true;
    }

    public function moduleName()
    {
        return 'projects';
    }

    public function subModuleName()
    {
        return 'tickets';
    }

    public function viewFolder()
    {
        return 'backend.system.ticket';
    }

    /**
     * Show ticket details.
     */
    public function show($project, $ticket)
    {
        $request = $this->defaultRequest();
        $request = app()->make($request);
        // For nested routes, get project from route parameter  
        $projectId = $project;
        $ticketId = $ticket;
        $this->setModuleId($projectId);

        try {
            $data = $this->service->showPageData($request, $ticketId);
            $data['project'] = \App\Models\Project::findOrFail($projectId);
            $data['breadcrumbs'] = $this->breadcrumbForIndex(false);
            $data['breadcrumbs'][] = [
                'title' => 'View',
                'active' => true,
            ];

            // Return JSON if AJAX request (for modal)
            if (request()->wantsJson() || request()->ajax()) {
                $ticket = $data['thisData'];

                return response()->json([
                    'success' => true,
                    'ticket' => [
                        'id' => $ticket->id,
                        'ticket_key' => $ticket->ticket_key,
                        'ticket_status_id' => $ticket->ticket_status_id,
                        'title' => $ticket->title,
                        'description' => $ticket->description,
                        'priority' => $ticket->priority,
                        'type' => $ticket->type,
                        'assignee_id' => $ticket->assignee_id,
                        'assignee' => $ticket->assignee,
                        'reporter' => $ticket->reporter,
                        'project' => $ticket->project,
                        'due_date' => $ticket->due_date ? $ticket->due_date->format('Y-m-d') : null,
                        'story_points' => $ticket->story_points,
                        'status' => $ticket->status,
                        'created_at' => $ticket->created_at->diffForHumans(),
                        'updated_at' => $ticket->updated_at->diffForHumans(),
                        'comments' => $ticket->comments()->with('user')->orderBy('created_at', 'desc')->get(),
                        'activities' => $ticket->activities()->with('user')->orderBy('created_at', 'desc')->take(10)->get(),
                        'attachments' => $ticket->attachments()->orderBy('created_at', 'desc')->get(),
                    ],
                ]);
            }

            return $this->renderView('show', $data);
        } catch (\Throwable $th) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage() ?? 'Something went wrong.',
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Show Global Kanban board view (all projects).
     */
    public function globalKanban(Request $request)
    {
        try {
            $data = $this->thisService->globalKanbanPageData($request);
            $data['breadcrumbs'] = [
                ['title' => 'Global Kanban Board', 'active' => true],
            ];

            return view('backend.system.ticket.global-kanban', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Show a list of all resources.
     * GET resources.
     */
    public function index(Request $request, $id = '')
    {
        // For nested routes, get project from route parameter
        $projectId = $request->route('project') ?? $id;
        $this->setModuleId($projectId);
        $request->merge(['project_id' => $projectId]);
        $data = $this->service->indexPageData($request);
        $data['breadcrumbs'] = $this->breadcrumbForIndex();

        return $this->renderView('index', $data);
    }

    /**
     * Render a form to be used for creating a new resource.
     * GET resources/create.
     */
    public function create()
    {
        $request = $this->defaultRequest();
        $request = app()->make($request);
        // For nested routes, get project from route parameter
        $projectId = request()->route('project') ?? $this->moduleId;
        $this->setModuleId($projectId);
        $request->merge(['project_id' => $projectId]);
        $data = $this->service->createPageData($request);
        $data['breadcrumbs'] = $this->breadcrumbForForm('Create');

        return $this->renderView('create', $data);
    }

    /**
     * Create/save a new resource.
     * POST resources.
     */
    public function store()
    {
        if (!empty($this->storeValidationRequest())) {
            $request = $this->storeValidationRequest();
        } else {
            $request = $this->defaultRequest();
        }
        $request = app()->make($request);
        // For nested routes, get project from route parameter
        $projectId = request()->route('project') ?? $this->moduleId;
        $this->setModuleId($projectId);
        $request->merge(['project_id' => $projectId]);
        try {
            $ticket = $this->service->store($request);

            // Reload ticket with relationships for email notification
            $ticket->refresh();
            $ticket->load(['project', 'ticketStatus', 'assignee']);

            // Send notification if ticket has an assignee
            if ($ticket->assignee_id && $ticket->assignee_id != auth()->id()) {
                $this->notificationService->notifyTicketAssignment($ticket, auth()->id());
            }

            return redirect($this->getUrl())->withErrors(['success' => 'Successfully created.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Render a form to update an existing resource.
     * GET resources/:id/edit.
     */
    public function edit($id)
    {
        $request = $this->defaultRequest();
        $request = app()->make($request);
        // For nested routes, get project from route parameter
        $projectId = request()->route('project') ?? $this->moduleId;
        $this->setModuleId($projectId);
        try {
            $data = $this->service->editPageData($request, $id);
            $data['breadcrumbs'] = $this->breadcrumbForForm('Edit');

            return $this->renderView('edit', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Update resource details.
     * PUT or PATCH resources/:id.
     */
    public function update($id)
    {
        // Get the correct ticket ID from route parameter
        $ticketId = request()->route('ticket') ?? $id;
        
        if (!empty($this->updateValidationRequest())) {
            $request = $this->updateValidationRequest();
        } elseif (!empty($this->storeValidationRequest())) {
            $request = $this->storeValidationRequest();
        } else {
            $request = $this->defaultRequest();
        }
        $request = app()->make($request);
        // For nested routes, get project from route parameter
        $projectId = request()->route('project') ?? $this->moduleId;
        $this->setModuleId($projectId);
        $request->merge(['project_id' => $projectId]);
        
        try {
            // Get ticket before update to check for changes
            $ticket = \App\Models\Ticket::findOrFail($ticketId);
            $oldAssigneeId = $ticket->assignee_id;
            $oldStatusId = $ticket->ticket_status_id;

            $updatedTicket = $this->service->update($request, $ticketId);

            // Reload ticket with relationships
            $updatedTicket->refresh();
            $updatedTicket->load(['project', 'ticketStatus', 'assignee']);

            // Check if assignee changed and send email notification
            if ($updatedTicket->assignee_id != $oldAssigneeId) {
                if ($updatedTicket->assignee_id && $updatedTicket->assignee_id != auth()->id()) {
                    $this->notificationService->notifyTicketAssignment($updatedTicket, auth()->id());
                }
            }

            // Check if status changed and send email notification
            if ($updatedTicket->ticket_status_id != $oldStatusId) {
                $oldStatus = \App\Models\TicketStatus::find($oldStatusId);
                $newStatus = $updatedTicket->ticketStatus;
                if ($oldStatus && $newStatus) {
                    $this->notificationService->notifyStatusChange(
                        $updatedTicket,
                        $oldStatus->name,
                        $newStatus->name,
                        auth()->id()
                    );
                }
            }

            // Return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ticket updated successfully',
                    'ticket' => $updatedTicket
                ]);
            }

            return redirect($this->getUrl())->withErrors(['success' => 'Successfully updated.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Delete a resource with id.
     * DELETE resources/:id.
     */
    public function destroy(Request $request, $id)
    {
        // For nested routes, get project from route parameter
        $projectId = request()->route('project') ?? $this->moduleId;
        $this->setModuleId($projectId);
        try {
            $response = $this->service->delete($request, $id);
            if (isset($response['error'])) {
                return redirect()->back()->withErrors(['error' => $response['error']]);
            }

            return redirect($this->getUrl())->withErrors(['success' => 'Successfully deleted.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Update ticket status (AJAX).
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'status_id' => 'required|exists:ticket_statuses,id',
            ]);

            $ticket = \App\Models\Ticket::findOrFail($request->ticket_id);
            $oldStatus = $ticket->ticketStatus;
            $ticket->ticket_status_id = $request->status_id;
            $ticket->save();

            // Log activity
            \App\Models\TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'description' => 'Changed status from "'.$oldStatus->name.'" to "'.$ticket->ticketStatus->name.'"',
                'old_value' => ['status_id' => $oldStatus->id, 'status_name' => $oldStatus->name],
                'new_value' => ['status_id' => $ticket->ticket_status_id, 'status_name' => $ticket->ticketStatus->name],
            ]);

            // Send notification
            $this->notificationService->notifyStatusChange($ticket, $oldStatus->name, $ticket->ticketStatus->name, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() ?? 'Failed to update ticket status',
            ], 400);
        }
    }

    /**
     * Update ticket assignee (AJAX).
     */
    public function updateAssignee(Request $request)
    {
        try {
            $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'assignee_id' => 'nullable|exists:users,id',
            ]);

            $ticket = \App\Models\Ticket::findOrFail($request->ticket_id);
            $oldAssignee = $ticket->assignee;
            $oldAssigneeId = $ticket->assignee_id;
            $ticket->assignee_id = $request->assignee_id;
            $ticket->save();

            // Reload ticket with relationships for email notification
            $ticket->refresh();
            $ticket->load(['project', 'ticketStatus', 'assignee']);

            // Log activity
            $newAssignee = $request->assignee_id ? \App\Models\User::find($request->assignee_id) : null;
            $oldAssigneeName = $oldAssignee ? $oldAssignee->name : 'Unassigned';
            $newAssigneeName = $newAssignee ? $newAssignee->name : 'Unassigned';

            \App\Models\TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'assignee_changed',
                'description' => 'Changed assignee from "'.$oldAssigneeName.'" to "'.$newAssigneeName.'"',
                'old_value' => ['assignee_id' => $oldAssigneeId, 'assignee_name' => $oldAssigneeName],
                'new_value' => ['assignee_id' => $ticket->assignee_id, 'assignee_name' => $newAssigneeName],
            ]);

            // Send email notification to new assignee (if assigned and different from old assignee and current user)
            if ($ticket->assignee_id && $ticket->assignee_id != auth()->id()) {
                $this->notificationService->notifyTicketAssignment($ticket, auth()->id());
            }

            return response()->json([
                'success' => true,
                'message' => 'Assignee updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() ?? 'Failed to update assignee',
            ], 400);
        }
    }

    /**
     * Attach a label to a ticket.
     */
    public function attachLabel(Request $request, $ticket)
    {
        try {
            $request->validate([
                'label_id' => 'required|exists:ticket_labels,id',
            ]);

            $ticket = \App\Models\Ticket::findOrFail($ticket);
            $ticket->labels()->attach($request->label_id);

            // Log activity
            $label = \App\Models\TicketLabel::find($request->label_id);
            \App\Models\TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'label_added',
                'description' => 'Added label "'.$label->name.'"',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Label attached successfully',
                'label' => $label,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Detach a label from a ticket.
     */
    public function detachLabel($ticket, $label)
    {
        try {
            $ticket = \App\Models\Ticket::findOrFail($ticket);
            $labelModel = \App\Models\TicketLabel::findOrFail($label);
            $ticket->labels()->detach($label);

            // Log activity
            \App\Models\TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'label_removed',
                'description' => 'Removed label "'.$labelModel->name.'"',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Label detached successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
