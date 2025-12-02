<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'ticket_key',
        'title',
        'description',
        'ticket_status_id',
        'priority',
        'type',
        'assignee_id',
        'reporter_id',
        'due_date',
        'story_points',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function labels()
    {
        return $this->belongsToMany(TicketLabel::class, 'ticket_label');
    }

    public function checklists()
    {
        return $this->hasMany(TicketChecklist::class)->orderBy('order', 'asc');
    }

    public function watchers()
    {
        return $this->belongsToMany(User::class, 'ticket_watchers');
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class)->orderBy('created_at', 'desc');
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class)->orderBy('logged_date', 'desc');
    }

    public function totalTimeSpent()
    {
        return $this->timeLogs()->sum('time_spent');
    }
}

