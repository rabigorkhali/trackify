<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportService
{
    public function pageData(Request $request): array
    {
        $tickets = $this->filteredQuery($request)
            ->with(['project', 'ticketStatus', 'assignee', 'reporter'])
            ->orderBy('created_at', 'DESC')
            ->get();

        $statuses = TicketStatus::orderBy('order')->get();

        return [
            'title' => 'Bug Report',
            'users' => User::where('status', 1)->orderBy('name')->get(),
            'projects' => Project::where('status', 1)->orderBy('name')->get(),
            'statuses' => $statuses,
            'tickets' => $tickets,
            'summaryByStatus' => $this->summarizeByStatus($tickets, $statuses),
            'summaryByProject' => $this->summarizeByProject($tickets, $statuses),
            'selectedUser' => $request->filled('user_id')
                ? User::find($request->get('user_id'))
                : null,
        ];
    }

    public function exportRows(Request $request): Collection
    {
        return $this->filteredQuery($request)
            ->with(['project', 'ticketStatus', 'assignee', 'reporter'])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    protected function filteredQuery(Request $request)
    {
        $query = Ticket::query();

        $userId = $request->get('user_id');
        $userRole = $request->get('user_role', 'assignee');
        $projectId = $request->get('project_id');
        $statusId = $request->get('ticket_status_id');
        $priority = $request->get('priority');
        $type = $request->get('type', 'bug');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if ($userId) {
            if ($userRole === 'reporter') {
                $query->where('reporter_id', $userId);
            } elseif ($userRole === 'both') {
                $query->where(function ($q) use ($userId) {
                    $q->where('assignee_id', $userId)
                        ->orWhere('reporter_id', $userId);
                });
            } else {
                $query->where('assignee_id', $userId);
            }
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($statusId) {
            $query->where('ticket_status_id', $statusId);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    protected function summarizeByStatus(Collection $tickets, Collection $statuses): array
    {
        $counts = $tickets->groupBy('ticket_status_id')->map->count();
        $summary = [];

        foreach ($statuses as $status) {
            $summary[] = [
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'count' => (int) ($counts[$status->id] ?? 0),
            ];
        }

        return $summary;
    }

    protected function summarizeByProject(Collection $tickets, Collection $statuses): array
    {
        $byProject = $tickets->groupBy('project_id');
        $summary = [];

        foreach ($byProject as $projectId => $projectTickets) {
            $project = $projectTickets->first()->project;
            $statusCounts = [];

            foreach ($statuses as $status) {
                $statusCounts[$status->id] = $projectTickets
                    ->where('ticket_status_id', $status->id)
                    ->count();
            }

            $summary[] = [
                'project_id' => $projectId,
                'project_name' => $project->name ?? 'Unknown',
                'project_key' => $project->key ?? '',
                'total' => $projectTickets->count(),
                'status_counts' => $statusCounts,
            ];
        }

        usort($summary, fn ($a, $b) => $b['total'] <=> $a['total']);

        return $summary;
    }
}
