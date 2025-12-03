<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'ticket_id',
        'project_id',
        'triggered_by',
        'data',
        'read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected $appends = ['icon', 'color'];

    // Notification types
    const TYPE_MENTION = 'mention';
    const TYPE_STATUS_CHANGE = 'status_change';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_COMMENT_MENTION = 'comment_mention';
    const TYPE_TICKET_UPDATE = 'ticket_update';
    const TYPE_COMMENT_ADDED = 'comment_added';

    /**
     * Get the user who receives the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who triggered the notification
     */
    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /**
     * Get the ticket associated with the notification
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the project associated with the notification
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_MENTION => 'ti-at',
            self::TYPE_STATUS_CHANGE => 'ti-flag',
            self::TYPE_ASSIGNMENT => 'ti-user-check',
            self::TYPE_COMMENT_MENTION => 'ti-message',
            self::TYPE_TICKET_UPDATE => 'ti-edit',
            self::TYPE_COMMENT_ADDED => 'ti-message-circle',
            default => 'ti-bell',
        };
    }

    /**
     * Get color based on notification type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            self::TYPE_MENTION => 'primary',
            self::TYPE_STATUS_CHANGE => 'warning',
            self::TYPE_ASSIGNMENT => 'success',
            self::TYPE_COMMENT_MENTION => 'info',
            self::TYPE_TICKET_UPDATE => 'secondary',
            self::TYPE_COMMENT_ADDED => 'info',
            default => 'primary',
        };
    }
}

