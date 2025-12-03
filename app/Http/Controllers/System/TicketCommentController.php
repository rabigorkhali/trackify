<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\TicketCommentService;
use App\Services\NotificationService;
use App\Http\Requests\System\TicketCommentRequest;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    protected $notificationService;

    public function __construct(private readonly TicketCommentService $ticketCommentService, NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Store a newly created comment
     */
    public function store(TicketCommentRequest $request)
    {
        try {
            \Log::info('TicketCommentController: Storing comment', [
                'ticket_id' => $request->ticket_id,
                'comment' => $request->comment,
                'user_id' => auth()->id()
            ]);
            
            $comment = $this->ticketCommentService->store($request);
            $ticket = \App\Models\Ticket::find($comment->ticket_id);
            
            \Log::info('TicketCommentController: Comment stored', [
                'comment_id' => $comment->id,
                'ticket_id' => $ticket->id
            ]);
            
            // Send notification to assignee and watchers about new comment
            $this->notificationService->notifyNewComment($ticket, $comment->comment, auth()->id());
            
            // Check for mentions in comment
            $this->notificationService->notifyMentions($comment->comment, $ticket, 'comment');
            
            // Handle mentions - send notifications and emails
            if ($request->has('mentioned_users')) {
                $mentionedUserIds = json_decode($request->mentioned_users, true);
                if (!empty($mentionedUserIds)) {
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

