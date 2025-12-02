<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\TicketCommentService;
use App\Http\Requests\System\TicketCommentRequest;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function __construct(private readonly TicketCommentService $ticketCommentService)
    {
    }

    /**
     * Store a newly created comment
     */
    public function store(TicketCommentRequest $request)
    {
        try {
            $comment = $this->ticketCommentService->store($request);
            
            // Handle mentions - send notifications and emails
            if ($request->has('mentioned_users')) {
                $mentionedUserIds = json_decode($request->mentioned_users, true);
                if (!empty($mentionedUserIds)) {
                    $ticket = \App\Models\Ticket::find($comment->ticket_id);
                    $commenter = auth()->user();
                    
                    foreach ($mentionedUserIds as $userId) {
                        $mentionedUser = \App\Models\User::find($userId);
                        if ($mentionedUser) {
                            // Create notification
                            \App\Models\TicketActivity::create([
                                'ticket_id' => $ticket->id,
                                'user_id' => auth()->id(),
                                'action' => 'user_mentioned',
                                'description' => "mentioned {$mentionedUser->name} in a comment",
                            ]);
                            
                            // Send email notification
                            try {
                                \Illuminate\Support\Facades\Mail::send('emails.ticket_mention', [
                                    'user' => $mentionedUser,
                                    'commenter' => $commenter,
                                    'ticket' => $ticket,
                                    'comment' => $comment,
                                ], function ($message) use ($mentionedUser, $ticket) {
                                    $message->to($mentionedUser->email)
                                            ->subject("You were mentioned in ticket {$ticket->ticket_key}");
                                });
                            } catch (\Exception $e) {
                                \Log::error('Failed to send mention email: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }
            
            // Return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $comment
                ]);
            }
            
            return redirect()->back()->withErrors(['success' => 'Comment added successfully.']);
        } catch (\Throwable $th) {
            // Return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage() ?? 'Something went wrong'
                ], 400);
            }
            
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        try {
            $request = app()->make(Request::class);
            $this->ticketCommentService->delete($request, $id);
            return redirect()->back()->withErrors(['success' => 'Comment deleted successfully.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }
}

