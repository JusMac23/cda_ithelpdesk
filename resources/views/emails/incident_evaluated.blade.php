@component('mail::message')
# New Incident Report has been Evaluated by DPO

**DBN Number:** {{ $data['dbn_number'] ?? 'N/A' }}
**PIC:** {{ $data['pic'] ?? 'N/A' }}

### Summary:
{{ $data['brief_summary'] ?? 'No summary available.' }}

@if(isset($data['dbn_id']))
@component('mail::button', ['url' => route('databreach.show', $data['dbn_id'])])
View Incident
@endcomponent
@else
_DBN ID not available._
@endif

Thanks,  
**CDA Data Breach Reporting System**
@endcomponent

