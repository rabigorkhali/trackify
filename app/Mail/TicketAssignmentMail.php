<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $user;
    public $assignedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, User $user, User $assignedBy)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Ensure ticket has relationships loaded
        if (!$this->ticket->relationLoaded('project')) {
            $this->ticket->load('project');
        }
        if (!$this->ticket->relationLoaded('ticketStatus')) {
            $this->ticket->load('ticketStatus');
        }

        return $this->subject("New Ticket Assigned: {$this->ticket->ticket_key}")
                    ->view('emails.ticket_assignment')
                    ->with([
                        'ticket' => $this->ticket,
                        'user' => $this->user,
                        'assignedBy' => $this->assignedBy,
                    ]);
    }
}

