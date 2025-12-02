<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'time_spent',
        'description',
        'logged_date',
    ];

    protected $casts = [
        'logged_date' => 'date',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTimeAttribute()
    {
        $hours = floor($this->time_spent / 60);
        $minutes = $this->time_spent % 60;
        return sprintf('%dh %dm', $hours, $minutes);
    }
}

