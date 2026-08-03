<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        $data = $this->reportService->pageData($request);

        return view('backend.system.report.index', $data);
    }

    public function export(Request $request): StreamedResponse
    {
        $tickets = $this->reportService->exportRows($request);
        $filename = 'bug-report-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($tickets) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Key',
                'Title',
                'Project',
                'Type',
                'Status',
                'Priority',
                'Assignee',
                'Reporter',
                'Due Date',
                'Created At',
            ]);

            foreach ($tickets as $ticket) {
                fputcsv($handle, [
                    $ticket->ticket_key,
                    $ticket->title,
                    $ticket->project->name ?? '',
                    ucfirst($ticket->type),
                    $ticket->ticketStatus->name ?? '',
                    ucfirst($ticket->priority),
                    $ticket->assignee->name ?? 'Unassigned',
                    $ticket->reporter->name ?? '',
                    optional($ticket->due_date)->format('Y-m-d'),
                    optional($ticket->created_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
