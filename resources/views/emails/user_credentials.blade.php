@component('mail::message')
<style>
    /* Import Figtree font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap');

    /* Apply Figtree font globally */
    body, h1, h2, h3, h4, h5, h6, p, a, strong, em, span, div {
        font-family: 'Figtree', Arial, sans-serif !important;
    }
</style>

# Hello {{ $user->name }},

An account has been created for you. Below are your login credentials:

<div style="background-color: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0; color: #333333;">
    <strong>Email:</strong> {{ $user->email }}<br>
    <div style="margin-top: 5px;"><strong>Password:</strong> {{ $password }}</div>
</div>

Please log in and consider changing your password immediately.

@component('mail::button', ['url' => route('login')])
Log In Now
@endcomponent 

This is an automated notification from the ICT Support Helpdesk System. Please do not reply to this email.
@endcomponent