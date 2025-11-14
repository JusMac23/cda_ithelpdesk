@component('mail::message')
# New Incident Report Submitted by Data Breach Response Team for Evaluation

**DBN Number:** {{ $data['dbn_number'] }}

### Summary:
{{ $data['brief_summary'] }}

Click the button below:

@component('mail::button', ['url' => route('databreach.show', $data['dbn_id'])])
View Incident
@endcomponent

Thanks,  
**CDA Data Breach Notification System**
@endcomponent
