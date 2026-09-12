<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CLientTicketNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('Your Ticket has been Submitted - Ticket Number: ' . $this->ticket->ticket_number)
                    ->markdown('emails.client_ticket_notification');
    }
}