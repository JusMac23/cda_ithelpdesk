@component('mail::message')
<style>
    /* Import Figtree font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap');

    /* Apply Figtree font to all text elements */
    body, h1, h2, h3, h4, h5, h6, p, a, strong, em, span, div {
        font-family: 'Figtree', sans-serif !important;
    }

    /* Ensure the button also uses Figtree font */
    .button {
        font-family: 'Figtree', sans-serif !important;
        font-weight: 600;
        text-decoration: none;
    }
</style>

# New Ticket Re-Assigned

Hello {{ $ticket->firstname }} {{ $ticket->lastname }},

<p>Your ticket has been re-assigned to <strong>{{ $ticket->re_assigned_to }}</strong> by <strong>{{ $assignedBy }}</strong>.</p>

<p>Please check the details below.</p>

**Ticket Number:** {{ $ticket->ticket_number }}  
**Name:** {{ $ticket->firstname }} {{ $ticket->lastname }}  
**Division:** {{ $ticket->division }}  
**Request:** {{ $ticket->request }}

@component('mail::button', ['url' => url('/login')])
View Ticket
@endcomponent

This is an automated notification from the ICT Support Helpdesk System. Please do not reply to this email.
@endcomponent