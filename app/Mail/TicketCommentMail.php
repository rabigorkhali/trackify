<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $user;
    public $commenter;
    public $comment;
    public $commentDate;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, User $user, User $commenter, $comment, $commentDate = null)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->commenter = $commenter;
        $this->comment = $comment;
        $this->commentDate = $commentDate ?? now()->diffForHumans();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("New Comment on Ticket: {$this->ticket->ticket_key}")
                    ->view('emails.ticket_comment')
                    ->with([
                        'ticket' => $this->ticket,
                        'user' => $this->user,
                        'commenter' => $this->commenter,
                        'comment' => $this->comment,
                        'commentDate' => $this->commentDate,
                    ]);
    }
}

