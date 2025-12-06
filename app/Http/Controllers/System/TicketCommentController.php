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
            
            // Handle mentions - send notifications and emails
            if ($request->has('mentioned_users')) {
                $mentionedUserIds = json_decode($request->mentioned_users, true);
                if (!empty($mentionedUserIds)) {
                    $commenter = auth()->user();
                    
                    foreach ($mentionedUserIds as $userId) {
                        $mentionedUser = \App\Models\User::find($userId);
                        if ($mentionedUser && $mentionedUser->email) {
                            // Create notification (this will also send email via NotificationService)
                            $this->notificationService->create(
                                $mentionedUser->id,
                                \App\Models\Notification::TYPE_COMMENT_MENTION,
                                'You were mentioned',
                                "You were mentioned in a comment on ticket: {$ticket->title}",
                                $ticket->id,
                                $ticket->project_id,
                                auth()->id(),
                                ['comment' => $comment->comment]
                            );
                            
                            // Log activity
                            \App\Models\TicketActivity::create([
                                'ticket_id' => $ticket->id,
                                'user_id' => auth()->id(),
                                'action' => 'user_mentioned',
                                'description' => "mentioned {$mentionedUser->name} in a comment",
                            ]);
                        }
                    }
                }
            } else {
                // Also check for mentions in comment text (fallback)
                $this->notificationService->notifyMentions($comment->comment, $ticket, 'comment');
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
    public function destroy(Request $request, $id)
    {
        try {
            $comment = \App\Models\TicketComment::findOrFail($id);
            
            // Check if user is the comment owner
            if ($comment->user_id !== auth()->id()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only delete your own comments'
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'You can only delete your own comments.']);
            }
            
            // Delete attachments from storage if they exist
            if ($comment->attachments && is_array($comment->attachments)) {
                foreach ($comment->attachments as $attachment) {
                    $filePath = is_array($attachment) ? ($attachment['path'] ?? '') : $attachment;
                    if ($filePath) {
                        $fullPath = public_path($filePath);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                            \Log::info('Deleted attachment file: ' . $filePath);
                        }
                    }
                }
            }
            
            // Delete the comment
            $comment->delete();
            
            \Log::info('Comment deleted', [
                'comment_id' => $id,
                'ticket_id' => $comment->ticket_id,
                'user_id' => auth()->id()
            ]);
            
            // Return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment deleted successfully'
                ]);
            }
            
            return redirect()->back()->withErrors(['success' => 'Comment deleted successfully.']);
        } catch (\Throwable $th) {
            \Log::error('Error deleting comment: ' . $th->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage() ?? 'Something went wrong'
                ], 400);
            }
            
            return redirect()->back()->withErrors(['error' => $th->getMessage() ?? 'Something went wrong.']);
        }
    }
}

