@component('mail::message')
<style>
    /* Import Figtree font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap');

    /* Apply Figtree font globally to all elements */
    body, h1, h2, h3, h4, h5, h6, p, a, strong, em, span, div {
        font-family: 'Figtree', sans-serif !important;
    }

    /* Ensure buttons use Figtree as well */
    .button {
        font-family: 'Figtree', sans-serif !important;
        font-weight: 600;
        text-decoration: none;
    }
</style>

# New Ticket Resolved

Hello {{ $ticket->firstname . ' ' . $ticket->lastname }},

This is to inform you that your request has been successfully resolved.

We request that you upload your e-signature for confirmation. Please click Upload Signature below to proceed:

@component('mail::button', ['url' => route('tickets.client_signature', $ticket->ticket_id)])
Upload Signature
@endcomponent

We request also to fill out the feedback form to help us improve our services. Please click Feedback Form below to proceed:

@component('mail::button', ['url' => 'https://docs.google.com/forms/d/e/1FAIpQLSf4wzO96Dzzoj68n0OIYvEYRupsHZLPxnn8QHBV8jRWjSpzqQ/viewform'])
Feedback Form
@endcomponent

**Ticket Number:** {{ $ticket->ticket_number }}  
**Name:** {{ $ticket->firstname }} {{ $ticket->lastname }}  
**Division:** {{ $ticket->division }}  
**Request:** {{ $ticket->request }}

This is an automated notification from the ICT Support Helpdesk System. Please do not reply to this email.
@endcomponent
