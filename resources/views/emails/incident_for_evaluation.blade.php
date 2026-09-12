@component('mail::message')
# New Incident Report Submitted by Data Breach Response Team for Evaluation

**DBN Number:** {{ $data['dbn_number'] ?? 'N/A' }}

### Summary:
{{ $data['brief_summary'] ?? 'No summary available.' }}

@if(isset($data['dbn_id']))
@component('mail::button', ['url' => route('databreach.show', $data['dbn_id'])])
View Incident
@endcomponent
@else
_DBN ID not available._
@endif

This is an automated notification from the ICT Support Helpdesk System. Please do not reply to this email.
@endcomponent
