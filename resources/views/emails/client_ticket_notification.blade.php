@component('mail::message')
<style>
    /* Import Figtree font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap');

    /* Apply Figtree font globally */
    body, h1, h2, h3, h4, h5, h6, p, a, strong, em, span, div {
        font-family: 'Figtree', sans-serif !important;
    }

    /* Style for the mail button */
    .button {
        font-family: 'Figtree', sans-serif !important;
        font-weight: 600;
        text-decoration: none;
    }
</style>

# Your Ticket Has Been Submitted

Hello {{ $ticket->firstname . ' ' . $ticket->lastname }},

Your ticket has been successfully submitted. Below are the details of your request:

**Ticket Number:** {{ $ticket->ticket_number }}  
**Name:** {{ $ticket->firstname }} {{ $ticket->lastname }}  
**Division:** {{ $ticket->division }}  
**Request:** {{ $ticket->request }}

To monitor the status of your ticket, please login to **https://icthelpdesk.cda.gov.ph/** using your Google account.

@component('mail::button', ['url' => url('/login')])
View Ticket
@endcomponent

This is an automated notification from the ICT Support Helpdesk System. Please do not reply to this email.
@endcomponent