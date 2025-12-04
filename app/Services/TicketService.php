<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class TicketService extends Service
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    public function indexPageData($request)
    {
        $projectId = $request->get('project_id');

        return [
            'thisDatas' => $this->getAllData($request),
            'project' => Project::findOrFail($projectId),
            'ticketStatuses' => TicketStatus::orderBy('order', 'asc')->get(),
            'users' => User::orderBy('name', 'asc')->get(),
            'projectId' => $projectId,
        ];
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        $keyword = $data->get('keyword');
        $show = $data->get('show');
        $projectId = $data->get('project_id');
        $statusId = $data->get('ticket_status_id');
        $assigneeId = $data->get('assignee_id');
        $priority = $data->get('priority');
        $type = $data->get('type');

        $query = $this->query();
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }

        // Always filter by project_id if provided
        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $table = $this->model->getTable();
        if ($keyword) {
            if (Schema::hasColumn($table, 'title')) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'ticket_key')) {
                $query->orWhereRaw('LOWER(ticket_key) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
        }
        if ($statusId) {
            $query->where('ticket_status_id', $statusId);
        }
        if ($assigneeId) {
            $query->where('assignee_id', $assigneeId);
        }
        if ($priority) {
            $query->where('priority', $priority);
        }
        if ($type) {
            $query->where('type', $type);
        }
        if ($pagination) {
            return $query->with(['project', 'ticketStatus', 'assignee', 'reporter'])
                ->orderBy('created_at', 'DESC')->paginate($show ?? 10);
        } else {
            return $query->with(['project', 'ticketStatus', 'assignee', 'reporter'])
                ->orderBy('created_at', 'DESC')->get();
        }
    }

    public function createPageData($request)
    {
        $projectId = $request->get('project_id');

        return [
            'project' => Project::findOrFail($projectId),
            'ticketStatuses' => TicketStatus::orderBy('order', 'asc')->get(),
            'users' => User::orderBy('name', 'asc')->get(),
        ];
    }

    public function editPageData($request, $id)
    {
        $thisData = $this->itemByIdentifier($id);

        return [
            'thisData' => $thisData,
            'project' => Project::findOrFail($thisData->project_id),
            'ticketStatuses' => TicketStatus::orderBy('order', 'asc')->get(),
            'users' => User::orderBy('name', 'asc')->get(),
        ];
    }

    public function showPageData($request, $id)
    {
        $ticket = $this->model->with([
            'project',
            'ticketStatus',
            'assignee',
            'reporter',
            'comments.user',
            'attachments.user',
            'labels',
            'checklists.completedByUser',
            'watchers',
            'activities.user',
            'timeLogs.user',
        ])->findOrFail($id);

        return [
            'thisData' => $ticket,
            'labels' => \App\Models\TicketLabel::where(function ($q) use ($ticket) {
                $q->where('project_id', $ticket->project_id)->orWhereNull('project_id');
            })->where('status', 1)->orderBy('name', 'asc')->get(),
            'users' => User::orderBy('name', 'asc')->get(),
        ];
    }

    public function globalKanbanPageData($request)
    {
        $projectId = $request->get('project_id');
        $assigneeId = $request->get('assignee_id');
        $priority = $request->get('priority');
        $dueDateFrom = $request->get('due_date_from');
        $dueDateTo = $request->get('due_date_to');
        $type = $request->get('type');

        // Get user's assigned project IDs
        $userProjectIds = \App\Models\ProjectMember::where('user_id', auth()->id())
            ->pluck('project_id')
            ->toArray();

        // Get all ticket statuses with their tickets
        $ticketStatuses = TicketStatus::where('status', 1)
            ->orderBy('order', 'asc')
            ->with(['tickets' => function ($query) use ($projectId, $assigneeId, $priority, $dueDateFrom, $dueDateTo, $type, $userProjectIds) {
                $query->where('status', 1);

                // Filter by user's assigned projects
                $query->whereIn('project_id', $userProjectIds);

                if ($projectId) {
                    $query->where('project_id', $projectId);
                }
                if ($assigneeId) {
                    $query->where('assignee_id', $assigneeId);
                }
                if ($priority) {
                    $query->where('priority', $priority);
                }
                if ($dueDateFrom && $dueDateTo) {
                    $query->whereBetween('due_date', [$dueDateFrom, $dueDateTo]);
                } elseif ($dueDateFrom) {
                    $query->whereDate('due_date', '>=', $dueDateFrom);
                } elseif ($dueDateTo) {
                    $query->whereDate('due_date', '<=', $dueDateTo);
                }
                if ($type) {
                    $query->where('type', $type);
                }

                $query->with(['assignee', 'project', 'labels', 'checklists', 'comments', 'attachments'])
                      ->orderBy('created_at', 'desc');
            }])
            ->get();

        return [
            'ticketStatuses' => $ticketStatuses,
            'projects' => Project::where('status', 1)->whereIn('id', $userProjectIds)->orderBy('name', 'asc')->get(),
            'users' => User::orderBy('name', 'asc')->get(),
            'labels' => \App\Models\TicketLabel::where('status', 1)->orderBy('name', 'asc')->get(),
            'title' => 'Global Kanban Board',
        ];
    }

    public function kanbanPageData($request, $projectId)
    {
        $assigneeId = $request->get('assignee_id');
        $priority = $request->get('priority');

        // Get all ticket statuses with their tickets
        $ticketStatuses = TicketStatus::where('status', 1)
            ->orderBy('order', 'asc')
            ->with(['tickets' => function ($query) use ($projectId, $assigneeId, $priority) {
                $query->where('status', 1);
                $query->where('project_id', $projectId); // Always filter by project
                if ($assigneeId) {
                    $query->where('assignee_id', $assigneeId);
                }
                if ($priority) {
                    $query->where('priority', $priority);
                }
                $query->with(['assignee', 'project', 'labels', 'checklists', 'comments', 'attachments'])->orderBy('created_at', 'desc');
            }])
            ->get();

        $project = Project::findOrFail($projectId);

        return [
            'ticketStatuses' => $ticketStatuses,
            'project' => $project,
            'users' => User::orderBy('name', 'asc')->get(),
            'labels' => \App\Models\TicketLabel::where(function ($q) use ($projectId) {
                $q->where('project_id', $projectId)->orWhereNull('project_id');
            })->where('status', 1)->orderBy('name', 'asc')->get(),
        ];
    }

    public function store($request)
    {
        $data = $request->except('_token');
        $data['reporter_id'] = auth()->id();

        // Generate ticket key
        $project = Project::findOrFail($data['project_id']);
        $lastTicket = Ticket::where('project_id', $project->id)
            ->orderBy('id', 'desc')
            ->first();
        $ticketNumber = $lastTicket ? (int) substr($lastTicket->ticket_key, strrpos($lastTicket->ticket_key, '-') + 1) + 1 : 1;
        $data['ticket_key'] = $project->key.'-'.$ticketNumber;

        $ticket = $this->model->create($data);

        // Log activity
        \App\Models\TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'Created ticket',
        ]);

        return $ticket;
    }
}
