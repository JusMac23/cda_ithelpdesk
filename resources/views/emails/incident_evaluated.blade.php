@component('mail::message')
# New Incident Report has been Evaluated by DPO

**DBN Number:** {{ $data['dbn_number'] }}
**PIC:** {{ $data['pic'] }}

### Summary:
{{ $data['brief_summary'] }}

Click the button below:

@component('mail::button', ['url' => route('databreach.show', $data['dbn_id'])])
View Incident
@endcomponent

Thanks,  
**CDA Data Breach Notification System**
@endcomponent
