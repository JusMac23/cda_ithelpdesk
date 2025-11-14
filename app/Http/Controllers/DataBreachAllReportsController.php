<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBreachNotification;
use App\Models\DatabreachForAssessment;
use App\Models\DatabreachTeam;
use App\Models\Role;
use App\Models\Users;
use App\Mail\IncidentSubmitted; 
use App\Mail\IncidentForEvaluation;
use App\Mail\IncidentEvaluated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Mail;

class DataBreachAllReportsController extends Controller
{
    // Handle Provide List
    public function index(Request $request)
    {
        $status = $request->input('status');
        $region = $request->input('pic');

        // Start the query with a join between notifications and team
        $query = DataBreachNotification::query()
            ->leftJoin('databreach_dbrt_team', function ($join) {
                $join->on('databreach_dbrt_team.region', '=', 'databreach_notifications.pic');
            })
            ->select('databreach_notifications.*')
            ->distinct(); 

        // Apply both filters together
        if (!empty($status) && !empty($region)) {
            $query->where('databreach_notifications.status', $status)
                ->where('databreach_dbrt_team.region', $region);
        } 
        // Allow filtering individually too
        elseif (!empty($status)) {
            $query->where('databreach_notifications.status', $status);
        } 
        elseif (!empty($region)) {
            $query->where('databreach_dbrt_team.region', $region);
        }

        // Get paginated results
        $notifications = $query->orderBy('databreach_notifications.created_at', 'desc')
            ->paginate(10)
            ->appends([
                'status' => $status,
                'pic' => $region,
            ]);

        // Dropdown for region filter
        $pic = DatabreachTeam::select('region')
            ->distinct()
            ->orderBy('region', 'asc')
            ->pluck('region')
            ->filter()
            ->values();

        return view('databreach.index', compact('notifications', 'pic'));
    }

    // Handle Overview
    public function overview()
    {
        $causes = [
            'Theft', 'Identity Fraud', 'Sabotage / Physical Damage', 'Malicious Code', 'Hacking',
            'Misuse of Resources', 'Hardware Failure', 'Software Failure', 'Communication Failure',
            'Natural Disaster', 'Design Error', 'User Error', 'Operations Error',
            'Software Maintenance Error', 'Third Party / Service Provider', 'Others',
        ];

        $causeCounts = DataBreachNotification::select('specific_cause', DB::raw('COUNT(*) as total'))
            ->groupBy('specific_cause')
            ->pluck('total', 'specific_cause');

        $causeCards = collect($causes)->map(function ($specific_cause) use ($causeCounts) {
            $icons = [
                'Theft' => 'fa-lock',
                'Identity Fraud' => 'fa-id-card',
                'Sabotage / Physical Damage' => 'fa-hammer',
                'Malicious Code' => 'fa-bug',
                'Hacking' => 'fa-user-secret',
                'Misuse of Resources' => 'fa-network-wired',
                'Hardware Failure' => 'fa-microchip',
                'Software Failure' => 'fa-code',
                'Communication Failure' => 'fa-wifi',
                'Natural Disaster' => 'fa-cloud-bolt',
                'Design Error' => 'fa-drafting-compass',
                'User Error' => 'fa-user-xmark',
                'Operations Error' => 'fa-cogs',
                'Software Maintenance Error' => 'fa-screwdriver-wrench',
                'Third Party / Service Provider' => 'fa-people-arrows',
                'Others' => 'fa-ellipsis-h',
            ];

            return [
                'label' => $specific_cause,
                'icon' => $icons[$specific_cause] ?? 'fa-circle-question',
                'count' => $causeCounts[$specific_cause] ?? 0,
            ];
        })->toArray();

        $recentlyReported = DataBreachNotification::where('status', 'Reported')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('databreach.overview_databreach', compact('causeCards', 'recentlyReported'));
    }

    // Handle Create
    public function create()
    {
        return view('databreach.create_databreach');
    }

    // Handle Store
   public function store(Request $request)
    {
        // CAPTCHA Validation
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!($response->json()['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])
                        ->withInput();
        }

        // Validate user inputs
        $data = $request->validate([
            'sender_fullname'       => 'required|string|max:255',
            'sender_email'          => 'required|email|max:255',
            'date_occurrence'       => 'required|date',
            'date_discovery'        => 'required|date',
            'date_notification'     => 'required|date',
            'pic'                   => 'required|string|max:255',
            'brief_summary'         => 'required|string',
        ]);

        $data['status'] = 'For Assessment';
        $data['hundred_plus'] = 0;

        $data['email'] = $data['sender_email'];

        $year = now()->year;
        $lastNumber = DatabreachForAssessment::whereYear('created_at', $year)
            ->orderBy('dbn_id', 'desc')
            ->value('dbn_number');

        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber, strrpos($lastNumber, '-') + 1);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        $formattedSeq = str_pad($nextSeq, 2, '0', STR_PAD_LEFT);
        $data['dbn_number'] = "CDA-DBN-{$year}-{$formattedSeq}";

        DatabreachForAssessment::create($data);
    
        DB::table('databreach_notifications')->insert([
            'dbn_number'            => $data['dbn_number'],
            'sender_fullname'       => $data['sender_fullname'],
            'sender_email'          => $data['sender_email'],
            'date_occurrence'       => $data['date_occurrence'],
            'date_discovery'        => $data['date_discovery'],
            'date_notification'     => $data['date_notification'],
            'pic'                   => $data['pic'],
            'brief_summary'         => $data['brief_summary'],
            'status'                => $data['status'],
        ]);

        // Send email to ALL Databreach Team members 
        $teamEmails = DataBreachTeam::whereNotNull('email')->pluck('email');

        foreach ($teamEmails as $email) {
            Mail::to($email)->send(new IncidentSubmitted($data));
        }

        return redirect()->route('databreach.index')
            ->with('success', "Incident report submitted successfully. Status: For Assessment.");

    }

    // Handle View
    public function show($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);
        return view('databreach.view_databreach', compact('notification'));
    }

    // Handle Assessment
    public function assess($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        // Decode JSON into array for Blade display
        if (is_string($notification->notification_type_description)) {
            $notification->notification_type_description = json_decode($notification->notification_type_description, true);
        }

        $notification = DataBreachNotification::findOrFail($dbn_id);

        $region = $notification->pic;
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();

        $notification->team_email = $team ? $team->email : null;

        return view('databreach.assess_databreach', compact('notification'));
    }

    public function assess_getDbrtEmail($region)
    {
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();

        return response()->json([
            'email' => $team ? $team->email : null,
        ]);
    }

    // Handle Update
    public function update_assessment(Request $request, $dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        $data = $request->validate([
            'dbn_number'                        => 'required|string|max:100',
            'pic'                               => 'required|string|max:255',
            'email'                             => 'required|email|max:255',
            'representative'                    => 'required|string|max:255',
            'representative_email_address'      => 'required|email|max:255',
            'date_occurrence'                   => 'required|date',
            'date_discovery'                    => 'required|date',
            'date_notification'                 => 'required|date',
            'brief_summary'                     => 'required|string',
            'notification_type'                 => 'required|string|max:255',
            'notification_type_description'     => 'nullable|array',
            'notification_type_description.*'   => 'string|max:255',
            'sector_name'                       => 'required|string|max:255',
            'subsector_name'                    => 'required|string|max:255',
            'timeliness'                        => 'required|string|max:255',
            'general_cause'                     => 'nullable|string|max:255',
            'specific_cause'                    => 'nullable|string|max:255',
            'with_request'                      => 'required|in:Yes,No',
            'how_breach_occured'                => 'required|string',
            'chronology'                        => 'required|string',
            'num_records'                       => 'required|integer',
            'hundred_plus'                      => 'nullable|boolean',
            'num_records_provide_details'       => 'required|string',
            'description_nature'                => 'required|string',
            'likely_consequences'               => 'required|string',
            'dpo'                               => 'required|string|max:255',
            'spi'                               => 'required|string|max:255',
            'other_info'                        => 'required|string',
            'measures_to_address'               => 'required|string',
            'measures_to_secure'                => 'required|string',
            'actions_to_mitigate'               => 'required|string',
            'actions_to_inform'                 => 'required|string',
            'actions_to_prevent'                => 'required|string',
            'record_type'                       => 'required|string|max:255',
            'data_subjects'                     => 'required|string|max:255',
        ]);

        if (isset($data['notification_type_description']) && is_array($data['notification_type_description'])) {
            $data['notification_type_description'] = json_encode($data['notification_type_description']);
        }

        $data['status'] = 'For Evaluation';
        $notification->update($data);

        // Get the role ID for DPO
        $dpoRoleId = Role::where('name', 'DPO')->value('id');

        // Get emails of all users with DPO role
        $emailDPO = User::where('role_id', $dpoRoleId)->pluck('email');

        // Send email to each DPO
        foreach ($emailDPO as $email) {
            Mail::to($email)->send(new IncidentForEvaluation($data));
        }

        return redirect()->route('databreach.index')
            ->with('success', 'Incident report assessed successfully. Status: For Evaluation by DPO.');
    }

    // Handle Evaluation
    public function evaluate($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        // Decode JSON into array for Blade display
        if (is_string($notification->notification_type_description)) {
            $notification->notification_type_description = json_decode($notification->notification_type_description, true);
        }

        $notification = DataBreachNotification::findOrFail($dbn_id);

        $region = $notification->pic;
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();

        $notification->team_email = $team ? $team->email : null;

        return view('databreach.evaluate_databreach', compact('notification'));
    }

    public function evaluate_getDbrtEmail($region)
    {
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();

        return response()->json([
            'email' => $team ? $team->email : null,
        ]);
    }

    // Handle Update
    public function update_evaluation(Request $request, $dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        $data = $request->validate([
            'dbn_number'                        => 'required|string|max:100',
            'pic'                               => 'required|string|max:255',
            'email'                             => 'required|email|max:255',
            'representative'                    => 'required|string|max:255',
            'representative_email_address'      => 'required|email|max:255',
            'date_occurrence'                   => 'required|date',
            'date_discovery'                    => 'required|date',
            'date_notification'                 => 'required|date',
            'brief_summary'                     => 'required|string',
            'notification_type'                 => 'required|string|max:255',
            'notification_type_description'     => 'nullable|array',
            'notification_type_description.*'   => 'string|max:255',
            'sector_name'                       => 'required|string|max:255',
            'subsector_name'                    => 'required|string|max:255',
            'timeliness'                        => 'required|string|max:255',
            'general_cause'                     => 'nullable|string|max:255',
            'specific_cause'                    => 'nullable|string|max:255',
            'with_request'                      => 'required|in:Yes,No',
            'how_breach_occured'                => 'required|string',
            'chronology'                        => 'required|string',
            'num_records'                       => 'required|integer',
            'hundred_plus'                      => 'nullable|boolean',
            'num_records_provide_details'       => 'required|string',
            'description_nature'                => 'required|string',
            'likely_consequences'               => 'required|string',
            'dpo'                               => 'required|string|max:255',
            'spi'                               => 'required|string|max:255',
            'other_info'                        => 'required|string',
            'measures_to_address'               => 'required|string',
            'measures_to_secure'                => 'required|string',
            'actions_to_mitigate'               => 'required|string',
            'actions_to_inform'                 => 'required|string',
            'actions_to_prevent'                => 'required|string',
            'record_type'                       => 'required|string|max:255',
            'data_subjects'                     => 'required|string|max:255',
        ]);

        if (isset($data['notification_type_description']) && is_array($data['notification_type_description'])) {
            $data['notification_type_description'] = json_encode($data['notification_type_description']);
        }
    
        $notification->update($data);

        // Send email to ALL Databreach Team
        $teamEmails = DataBreachTeam::whereNotNull('email')->pluck('email');

        foreach ($teamEmails as $email) {
            Mail::to($email)->send(new IncidentEvaluated($data));
        }

        return redirect()->route('databreach.index')
            ->with('success', 'Incident report evaluated successfully. Status: For reporting to the NPC by the DPO.');
    }


    // Handle Delete
    public function destroy($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);
        $notification->delete();

        return redirect()->route('databreach.index')
            ->with('success', 'Notification deleted successfully.');
    }
}
