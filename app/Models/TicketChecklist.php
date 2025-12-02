<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'title',
        'description',
        'is_completed',
        'order',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function completedByUser()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}

