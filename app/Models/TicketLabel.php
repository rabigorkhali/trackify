<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'project_id',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_label');
    }
}

