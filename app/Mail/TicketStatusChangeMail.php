<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $user;
    public $changedBy;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, User $user, User $changedBy, $oldStatus, $newStatus)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->changedBy = $changedBy;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Ticket Status Changed: {$this->ticket->ticket_key}")
                    ->view('emails.ticket_status_change')
                    ->with([
                        'ticket' => $this->ticket,
                        'user' => $this->user,
                        'changedBy' => $this->changedBy,
                        'oldStatus' => $this->oldStatus,
                        'newStatus' => $this->newStatus,
                    ]);
    }
}

