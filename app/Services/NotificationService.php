<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use App\Mail\TicketAssignmentMail;
use App\Mail\TicketStatusChangeMail;
use App\Mail\TicketCommentMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create($userId, $type, $title, $message, $ticketId = null, $projectId = null, $triggeredBy = null, $data = [])
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'ticket_id' => $ticketId,
            'project_id' => $projectId,
            'triggered_by' => $triggeredBy ?? auth()->id(),
            'data' => $data,
        ]);

        // Send email notification
        $this->sendEmailNotification($notification);

        return $notification;
    }

    /**
     * Send email notification based on notification type
     */
    protected function sendEmailNotification(Notification $notification)
    {
        try {
            $user = $notification->user;
            if (!$user || !$user->email) {
                \Log::warning('Cannot send email: User or email missing', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'has_user' => $user ? true : false,
                    'has_email' => $user && $user->email ? true : false,
                ]);
                return;
            }

            // Load ticket with necessary relationships
            $ticket = Ticket::with(['project', 'ticketStatus'])->find($notification->ticket_id);
            if (!$ticket) {
                \Log::warning('Cannot send email: Ticket not found', [
                    'notification_id' => $notification->id,
                    'ticket_id' => $notification->ticket_id,
                ]);
                return;
            }

            $triggeredBy = $notification->triggeredBy ?? auth()->user();
            if (!$triggeredBy) {
                // If triggeredBy is not set, try to get it from the notification
                $triggeredBy = User::find($notification->triggered_by);
                if (!$triggeredBy) {
                    \Log::warning('Cannot send email: TriggeredBy user not found', [
                        'notification_id' => $notification->id,
                        'triggered_by' => $notification->triggered_by,
                    ]);
                    return;
                }
            }

            switch ($notification->type) {
                case Notification::TYPE_ASSIGNMENT:
                    if ($ticket && $user && $triggeredBy) {
                        try {
                            \Log::info('Attempting to send assignment email', [
                                'ticket_id' => $ticket->id,
                                'ticket_key' => $ticket->ticket_key,
                                'user_email' => $user->email,
                                'user_name' => $user->name,
                                'assigned_by' => $triggeredBy->name,
                                'mail_driver' => config('mail.default'),
                            ]);
                            
                            // Send email synchronously (not queued)
                            Mail::to($user->email)->send(new TicketAssignmentMail($ticket, $user, $triggeredBy));
                            
                            \Log::info('Assignment email sent successfully', [
                                'ticket_id' => $ticket->id,
                                'user_email' => $user->email,
                            ]);
                        } catch (\Exception $e) {
                            // Log error for debugging
                            \Log::error('Failed to send assignment email', [
                                'ticket_id' => $ticket->id,
                                'user_email' => $user->email,
                                'error' => $e->getMessage(),
                                'error_file' => $e->getFile(),
                                'error_line' => $e->getLine(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                            // Don't re-throw - we don't want email failures to break the assignment
                        }
                    } else {
                        \Log::warning('Cannot send assignment email - missing data', [
                            'has_ticket' => $ticket ? true : false,
                            'has_user' => $user ? true : false,
                            'has_triggeredBy' => $triggeredBy ? true : false,
                        ]);
                    }
                    break;

                case Notification::TYPE_STATUS_CHANGE:
                    if ($ticket && $user && $triggeredBy) {
                        $data = $notification->data ?? [];
                        $oldStatus = $data['old_status'] ?? 'Unknown';
                        $newStatus = $data['new_status'] ?? 'Unknown';
                        Mail::to($user->email)->send(new TicketStatusChangeMail($ticket, $user, $triggeredBy, $oldStatus, $newStatus));
                    }
                    break;

                case Notification::TYPE_COMMENT_ADDED:
                    if ($ticket && $user && $triggeredBy) {
                        $data = $notification->data ?? [];
                        $comment = $data['comment'] ?? '';
                        Mail::to($user->email)->send(new TicketCommentMail($ticket, $user, $triggeredBy, $comment));
                    }
                    break;

                case Notification::TYPE_COMMENT_MENTION:
                case Notification::TYPE_MENTION:
                    // Mentions are handled separately in TicketCommentController
                    // But we can still send email here if needed
                    if ($ticket && $user && $triggeredBy) {
                        // Use the existing ticket_mention template
                        try {
                            $commentData = $notification->data['comment'] ?? $notification->message;
                            Mail::send('emails.ticket_mention', [
                                'user' => $user,
                                'commenter' => $triggeredBy,
                                'ticket' => $ticket,
                                'comment' => (object)[
                                    'comment' => $commentData,
                                    'created_at' => $notification->created_at
                                ],
                            ], function ($message) use ($user, $ticket) {
                                $message->to($user->email)
                                        ->subject("You were mentioned in ticket {$ticket->ticket_key}");
                            });
                        } catch (\Exception $e) {
                            // Silently fail - don't break the notification process
                        }
                    }
                    break;
            }
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Failed to send email notification', [
                'notification_id' => $notification->id,
                'type' => $notification->type,
                'user_id' => $notification->user_id,
                'ticket_id' => $notification->ticket_id,
                'error' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Notify user about ticket assignment
     */
    public function notifyTicketAssignment(Ticket $ticket, $assignedBy)
    {
        if ($ticket->assignee_id) {
            $assignee = User::find($ticket->assignee_id);

            if ($assignee && $assignee->email) {
                // Ensure ticket has all relationships loaded
                if (!$ticket->relationLoaded('project')) {
                    $ticket->load('project');
                }
                if (!$ticket->relationLoaded('ticketStatus')) {
                    $ticket->load('ticketStatus');
                }

                \Log::info('NotificationService: Creating assignment notification', [
                    'ticket_id' => $ticket->id,
                    'assignee_id' => $assignee->id,
                    'assignee_email' => $assignee->email,
                    'assigned_by' => $assignedBy,
                    'is_self_assignment' => ($ticket->assignee_id == $assignedBy),
                ]);

                $this->create(
                    $ticket->assignee_id,
                    Notification::TYPE_ASSIGNMENT,
                    'New Ticket Assigned',
                    "You have been assigned to ticket: {$ticket->title}",
                    $ticket->id,
                    $ticket->project_id,
                    $assignedBy
                );
            } else {
                \Log::warning('NotificationService: Cannot send assignment email - no assignee or email', [
                    'ticket_id' => $ticket->id,
                    'assignee_id' => $ticket->assignee_id,
                    'assignee_exists' => $assignee ? true : false,
                    'has_email' => $assignee && $assignee->email ? true : false,
                ]);
            }
        } else {
            \Log::warning('NotificationService: Cannot send assignment email - no assignee_id', [
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    /**
     * Notify user about status change
     */
    public function notifyStatusChange(Ticket $ticket, $oldStatus, $newStatus, $changedBy)
    {
        // Notify assignee
        if ($ticket->assignee_id && $ticket->assignee_id != $changedBy) {
            $assignee = User::find($ticket->assignee_id);
            if ($assignee && $assignee->email) {
            $this->create(
                $ticket->assignee_id,
                Notification::TYPE_STATUS_CHANGE,
                'Ticket Status Changed',
                "Ticket \"{$ticket->title}\" status changed from {$oldStatus} to {$newStatus}",
                $ticket->id,
                $ticket->project_id,
                $changedBy,
                [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]
            );
            }
        }

        // Notify watchers
        $this->notifyWatchers($ticket, 'Ticket Status Changed', "Status changed from {$oldStatus} to {$newStatus}", $changedBy);
    }

    /**
     * Notify users mentioned in ticket or comment
     */
    public function notifyMentions($content, Ticket $ticket, $type = 'ticket')
    {
        // Extract mentions from content (e.g., @username or @user_id)
        preg_match_all('/@(\w+)/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $mention) {
                // Try to find user by name (case-insensitive)
                $user = User::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($mention) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($mention) . '%'])
                    ->first();

                if ($user && $user->id != auth()->id() && $user->email) {
                    $notificationType = $type === 'comment' 
                        ? Notification::TYPE_COMMENT_MENTION 
                        : Notification::TYPE_MENTION;

                    $message = $type === 'comment'
                        ? "You were mentioned in a comment on ticket: {$ticket->title}"
                        : "You were mentioned in ticket: {$ticket->title}";

                    $this->create(
                        $user->id,
                        $notificationType,
                        'You were mentioned',
                        $message,
                        $ticket->id,
                        $ticket->project_id,
                        auth()->id()
                    );
                }
            }
        }
    }

    /**
     * Notify about new comment
     */
    public function notifyNewComment(Ticket $ticket, $comment, $commentedBy)
    {
        // Notify assignee
        if ($ticket->assignee_id && $ticket->assignee_id != $commentedBy) {
            $assignee = User::find($ticket->assignee_id);
            if ($assignee && $assignee->email) {
            $this->create(
                $ticket->assignee_id,
                Notification::TYPE_COMMENT_ADDED,
                'New Comment on Ticket',
                "New comment added to ticket: {$ticket->title}",
                $ticket->id,
                $ticket->project_id,
                $commentedBy,
                    ['comment' => $comment]
            );
            }
        }

        // Notify watchers
        $this->notifyWatchers($ticket, 'New Comment', 'A new comment was added', $commentedBy);
    }

    /**
     * Notify ticket watchers
     */
    public function notifyWatchers(Ticket $ticket, $title, $message, $triggeredBy = null)
    {
        $watchers = $ticket->watchers()->where('user_id', '!=', $triggeredBy ?? auth()->id())->get();

        foreach ($watchers as $watcher) {
            $watcherUser = User::find($watcher->user_id);
            if ($watcherUser && $watcherUser->email) {
            $this->create(
                $watcher->user_id,
                Notification::TYPE_TICKET_UPDATE,
                $title,
                $message . " in ticket: {$ticket->title}",
                $ticket->id,
                $ticket->project_id,
                $triggeredBy ?? auth()->id()
            );
            }
        }
    }

    /**
     * Notify about ticket update
     */
    public function notifyTicketUpdate(Ticket $ticket, $changes, $updatedBy)
    {
        $changesList = implode(', ', array_keys($changes));
        
        // Notify assignee
        if ($ticket->assignee_id && $ticket->assignee_id != $updatedBy) {
            $assignee = User::find($ticket->assignee_id);
            if ($assignee && $assignee->email) {
            $this->create(
                $ticket->assignee_id,
                Notification::TYPE_TICKET_UPDATE,
                'Ticket Updated',
                "Ticket \"{$ticket->title}\" was updated. Changes: {$changesList}",
                $ticket->id,
                $ticket->project_id,
                $updatedBy,
                ['changes' => $changes]
            );
            }
        }

        // Notify watchers
        $this->notifyWatchers($ticket, 'Ticket Updated', "Changes: {$changesList}", $updatedBy);
    }
}

