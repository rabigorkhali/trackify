<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create($userId, $type, $title, $message, $ticketId = null, $projectId = null, $triggeredBy = null, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'ticket_id' => $ticketId,
            'project_id' => $projectId,
            'triggered_by' => $triggeredBy ?? auth()->id(),
            'data' => $data,
        ]);
    }

    /**
     * Notify user about ticket assignment
     */
    public function notifyTicketAssignment(Ticket $ticket, $assignedBy)
    {
        if ($ticket->assignee_id && $ticket->assignee_id != $assignedBy) {
            $this->create(
                $ticket->assignee_id,
                Notification::TYPE_ASSIGNMENT,
                'New Ticket Assigned',
                "You have been assigned to ticket: {$ticket->title}",
                $ticket->id,
                $ticket->project_id,
                $assignedBy
            );
        }
    }

    /**
     * Notify user about status change
     */
    public function notifyStatusChange(Ticket $ticket, $oldStatus, $newStatus, $changedBy)
    {
        // Notify assignee
        if ($ticket->assignee_id && $ticket->assignee_id != $changedBy) {
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
        
        \Log::info('NotificationService: Checking mentions', [
            'content' => $content,
            'matches' => $matches[1] ?? [],
            'ticket_id' => $ticket->id
        ]);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $mention) {
                // Try to find user by name (case-insensitive)
                $user = User::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($mention) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($mention) . '%'])
                    ->first();

                \Log::info('NotificationService: User lookup', [
                    'mention' => $mention,
                    'user_found' => $user ? $user->id : null,
                    'user_name' => $user ? $user->name : null
                ]);

                if ($user && $user->id != auth()->id()) {
                    $notificationType = $type === 'comment' 
                        ? Notification::TYPE_COMMENT_MENTION 
                        : Notification::TYPE_MENTION;

                    $message = $type === 'comment'
                        ? "You were mentioned in a comment on ticket: {$ticket->title}"
                        : "You were mentioned in ticket: {$ticket->title}";

                    $notification = $this->create(
                        $user->id,
                        $notificationType,
                        'You were mentioned',
                        $message,
                        $ticket->id,
                        $ticket->project_id,
                        auth()->id()
                    );
                    
                    \Log::info('NotificationService: Mention notification created', [
                        'notification_id' => $notification->id,
                        'user_id' => $user->id,
                        'ticket_id' => $ticket->id
                    ]);
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
            $this->create(
                $ticket->assignee_id,
                Notification::TYPE_COMMENT_ADDED,
                'New Comment on Ticket',
                "New comment added to ticket: {$ticket->title}",
                $ticket->id,
                $ticket->project_id,
                $commentedBy,
                ['comment' => substr($comment, 0, 100)]
            );
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

    /**
     * Notify about ticket update
     */
    public function notifyTicketUpdate(Ticket $ticket, $changes, $updatedBy)
    {
        $changesList = implode(', ', array_keys($changes));
        
        // Notify assignee
        if ($ticket->assignee_id && $ticket->assignee_id != $updatedBy) {
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

        // Notify watchers
        $this->notifyWatchers($ticket, 'Ticket Updated', "Changes: {$changesList}", $updatedBy);
    }
}

