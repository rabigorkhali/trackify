<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\UserService;

class UserController extends ResourceController
{
    public function __construct(private readonly UserService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\UserRequest';
    }

    public function moduleName()
    {
        return 'users';
    }

    public function viewFolder()
    {
        return 'backend.system.user';
    }
    
    /**
     * Display the specified user profile
     */
    public function show($id)
    {
        try {
            $user = \App\Models\User::with('role')->findOrFail($id);
            
            // Get user's tickets
            $assignedTickets = \App\Models\Ticket::where('assignee_id', $id)
                ->with(['project', 'ticketStatus'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            $reportedTickets = \App\Models\Ticket::where('reporter_id', $id)
                ->with(['project', 'ticketStatus'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            // Get user's comments
            $recentComments = \App\Models\TicketComment::where('user_id', $id)
                ->with(['ticket.project'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            // Get user's activities
            $recentActivities = \App\Models\TicketActivity::where('user_id', $id)
                ->with(['ticket.project'])
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
            
            $data = [
                'thisData' => $user,
                'assignedTickets' => $assignedTickets,
                'reportedTickets' => $reportedTickets,
                'recentComments' => $recentComments,
                'recentActivities' => $recentActivities,
                'title' => 'User Profile - ' . $user->name,
                'breadcrumbs' => [
                    ['title' => 'Users', 'link' => route('users.index')],
                    ['title' => $user->name, 'active' => true]
                ]
            ];
            
            return view('backend.system.user.show', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'User not found.']);
        }
    }
    
}
