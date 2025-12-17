@component('mail::message')
# New Incident Report

**DBN Number:** {{ $data['dbn_number'] }}  
**Sender:** {{ $data['sender_fullname'] }}  
**Email:** {{ $data['sender_email'] }}  
**PIC:** {{ $data['pic'] }}  
**Date of Occurrence:** {{ $data['date_occurrence'] }}  
**Date of Discovery:** {{ $data['date_discovery'] }}  
**Date of Notification:** {{ $data['date_notification'] }}

### Summary:
{{ $data['brief_summary'] }}


Thanks,  
**CDA Data Breach Reporting System**
@endcomponent
