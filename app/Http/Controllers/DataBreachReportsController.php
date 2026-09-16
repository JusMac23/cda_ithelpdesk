<?php

namespace App\Http\Controllers;

use FPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\UsersController;

use App\Models\DataBreachNotification;
use App\Models\DatabreachForAssessment;
use App\Models\DatabreachTeam;
use App\Models\Role;
use App\Models\User;

use Carbon\Carbon;

use App\Mail\IncidentSubmitted;     
use App\Mail\IncidentForEvaluation;
use App\Mail\IncidentEvaluated;

class DataBreachReportsController extends Controller
{
    // Handle View of Data Breach Notifications with Filters and Search
    public function index(Request $request)
    {
        $status = $request->input('status');
        $region = $request->input('pic');
        $year   = $request->input('year'); 
        $search = $request->input('search'); 

        $query = DataBreachNotification::query()
            ->leftJoin('databreach_dbrt_team', 'databreach_dbrt_team.region', '=', 'databreach_notifications.pic')
            ->select('databreach_notifications.*', 'databreach_dbrt_team.email as email')
            ->distinct(); 

        // --- Role-based Filtering ---
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin can view all records, no additional filtering needed
        } elseif ($user->hasRole('DBRT')) {
            $dbrtMember = DatabreachTeam::where('email', $user->email)->first();
            
            if ($dbrtMember && $dbrtMember->region) {
                $query->where('databreach_notifications.pic', $dbrtMember->region)
                    ->whereIn('databreach_notifications.status', ['Draft', 'For Assessment']);
            } else {
                $query->where('databreach_notifications.dbn_id', '<', 0);
            }
        } else {
            // All other roles cannot view records that are still in 'Draft' status
            $query->where('databreach_notifications.status', '!=', 'Draft');
        }

        // --- User-selected Form Filters ---
        $query->when(!empty($status), function ($q) use ($status) {
            $q->where('databreach_notifications.status', $status);
        });

        $query->when(!empty($region), function ($q) use ($region) {
            $q->where('databreach_notifications.pic', $region);
        });

        $query->when(!empty($year), function ($q) use ($year) {
            $q->whereYear('databreach_notifications.created_at', $year);
        });

        // --- Explicit Search Filter Chain ---
        $query->when(!empty($search), function ($q) use ($search) {
            $q->where(function($subQuery) use ($search) {
                $subQuery->where('databreach_notifications.sender_fullname', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.sender_email', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.dbn_number', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.pic', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.email', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.representative', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.representative_email_address', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.date_occurrence', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.date_discovery', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.date_notification', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.brief_summary', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.notification_type_description', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.sector_name', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.subsector_name', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.notification_type', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.timeliness', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.general_cause', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.specific_cause', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.general_incident', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.with_request', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.how_breach_occured', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.chronology', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.num_records', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.hundred_plus', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.num_records_provide_details', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.description_nature', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.likely_consequences', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.dpo', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.spi', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.other_info', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.measures_to_address', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.measures_to_secure', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.actions_to_mitigate', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.actions_to_inform', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.actions_to_prevent', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.record_type', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.data_subjects', 'like', '%' . $search . '%')
                    ->orWhere('databreach_notifications.status', 'like', '%' . $search . '%');
            });
        });

        $notifications = $query->orderBy('databreach_notifications.created_at', 'desc')
            ->paginate(10)
            ->appends([
                'status' => $status,
                'pic'    => $region,
                'year'   => $year, 
                'search' => $search, 
            ]);

        $teamRegions = DatabreachTeam::select('region')->distinct()->pluck('region');
        $notificationRegions = DataBreachNotification::select('pic')->distinct()->pluck('pic');
        
        $pic = $teamRegions->concat($notificationRegions)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $formYears = DataBreachNotification::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->values();

        return view('databreach.index', compact('notifications', 'pic', 'year', 'formYears', 'search'));
    }

    // Handle Overview
    public function overview(Request $request)
    {
        // Get filters from request
        $year = $request->input('year');
        $statusFilter = $request->input('status'); 
        $action = $request->input('action'); 

        // Base query
        $notificationsQuery = DataBreachNotification::query();

        // Apply annual filter
        if (!empty($year)) {
            $notificationsQuery->whereYear('created_at', $year);
        }

        // Apply status filter
        if (!empty($statusFilter)) {
            $notificationsQuery->where('status', $statusFilter);
        }

        // Calculate totals (for PDF and view)
        $totalNotifications = (clone $notificationsQuery)->count();
        $totalReported = (clone $notificationsQuery)->where('status', 'Reported')->count();
        $totalMandatory = (clone $notificationsQuery)->where('notification_type', 'Mandatory')->count();
        $totalVoluntary = (clone $notificationsQuery)->where('notification_type', 'Voluntary')->count();
        $totalOthers = (clone $notificationsQuery)->where('notification_type', 'Voluntary')->count();
        $totalOther = $totalNotifications - ($totalMandatory + $totalVoluntary);

        // Causes list
        $causes = [
            'Theft', 'Identity Fraud', 'Sabotage / Physical Damage', 'Malicious Code', 'Hacking',
            'Misuse of Resources', 'Hardware Failure', 'Software Failure', 'Communication Failure',
            'Natural Disaster', 'Design Error', 'User Error', 'Operations Error',
            'Software Maintenance Error', 'Third Party / Service Provider', 'Others',
        ];

        // Count notifications per general_incident
        $causeCounts = (clone $notificationsQuery)
            ->select('general_incident', DB::raw('COUNT(*) as total'))
            ->whereIn('status', ['For Evaluation', 'For Reporting to NPC', 'Reported'])
            ->groupBy('general_incident')
            ->pluck('total', 'general_incident');

        // Handle PDF generation
        if ($action === 'generate') {
            $pdf = new FPDF('P', 'mm', 'A4'); // Portrait
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14);

            // Get page width
            $pageWidth = $pdf->GetPageWidth();
            $marginInInches = 1;
            $margin = $marginInInches * 25.4;

            // Page and layout settings
            $margin = 25.4;
            $logoTop = 8;

            // Logo sizes
            $cdaWidth = 18;
            $bpWidth  = 22;

            // Space between logos and text
            $sideGap = 2;

            // Available content width
            $contentWidth = $pageWidth - ($margin * 2);

            // Text block width (you can tweak this)
            $textBlockWidth = 100;

            // Calculate center X of page
            $pageCenterX = $pageWidth / 2;

            // Text block X (centered)
            $textX = $pageCenterX - ($textBlockWidth / 2);

            // Logo positions
            $leftLogoX  = $textX - $sideGap - $cdaWidth;
            $rightLogoX = $textX + $textBlockWidth + $sideGap;

            // Logos
            $pdf->Image(public_path('images/CDA-logo-RA11364-PNG.png'), $leftLogoX, $logoTop, $cdaWidth);

            $pdf->Image(public_path('images/Bagong_Pilipinas_logo.png'), $rightLogoX, $logoTop, $bpWidth);

            // Header Text
            $pdf->SetXY($textX, 10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->MultiCell($textBlockWidth, 5, "COOPERATIVE DEVELOPMENT AUTHORITY", 0, 'C');

            $pdf->SetX($textX);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell($textBlockWidth, 5, "HEAD OFFICE", 0, 'C');

            $pdf->SetX($textX);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell( $textBlockWidth, 4, "827 Aurora Blvd., Service Road, Brgy. Immaculate Conception Cubao\n1111 Quezon City, Philippines", 0, 'C');

            $pdf->Ln(15); 

            $margin = 25.4; // 1 inch
            $pdf->SetLeftMargin($margin);
            $pdf->SetRightMargin($margin);
            $pdf->SetAutoPageBreak(true, $margin);

            $pageWidth = $pdf->GetPageWidth();
            $contentWidth = $pageWidth - ($margin * 2);

            // Header Section
            $displayYear = $year ?? (DataBreachNotification::latest('created_at')->first()?->created_at->format('Y') ?? 'YYYY');

            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell($contentWidth, 8, 'Year: ' . $displayYear, 0, 1);

            $pdf->SetFont('Arial', '', 12);

            // Two-column rows (50% / 50%)
            $halfWidth = $contentWidth / 2;

            $pdf->Cell($halfWidth, 8, 'Sector Name: GOVERNMENT', 1);
            $pdf->Cell($halfWidth, 8, 'Sub-Sector Name: Executive Department', 1, 1);

            $pdf->Cell($halfWidth, 8, 'City / Municipality: ', 1);
            $pdf->Cell($halfWidth, 8, 'PIC: Cooperative Development Authority', 1, 1);

            $pdf->Ln(5);

            // Summary Section
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($contentWidth, 8, 'Summary of Security Incidents and Privacy Breaches', 0, 1);

            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell($halfWidth, 8, 'A. Mandatory Breach Notification: ' . $totalMandatory, 1);
            $pdf->Cell($halfWidth, 8, 'B. Voluntary Data Breach Notification: ' . $totalVoluntary, 1, 1);

            $pdf->Cell($halfWidth, 8, 'C. Other Security Incidents: ' . $totalOther, 1);
            $pdf->Cell($halfWidth, 8, 'Total Security Incidents: ' . $totalNotifications, 1, 1);

            $pdf->Ln(5);

            // Specific Causes Table
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($contentWidth, 8, 'Specific Causes of Incidents', 0, 1);

            $pdf->SetFont('Arial', 'B', 10);

            $causeWidth = $contentWidth * 0.8;
            $countWidth = $contentWidth * 0.2;

            $pdf->Cell($causeWidth, 8, 'Cause', 1, 0, 'C');
            $pdf->Cell($countWidth, 8, 'Count', 1, 1, 'C');

            $pdf->SetFont('Arial', '', 10);

            foreach ($causes as $cause) {
                $count = $causeCounts[$cause] ?? 0;
                $pdf->Cell($causeWidth, 8, $cause, 1);
                $pdf->Cell($countWidth, 8, $count, 1, 1, 'C');
            }

            $filename = 'Data_Breach_Report_' . now()->format('dmY') . '.pdf';
            $pdf->Output('D', $filename);
            exit;
        }

        // Map causes to cards with icons for the view
        $causeCards = collect($causes)->map(function ($general_incident) use ($causeCounts) {
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
                'label' => $general_incident,
                'icon' => $icons[$general_incident] ?? 'fa-circle-question',
                'count' => $causeCounts[$general_incident] ?? 0,
            ];
        })->toArray();

        // Recently reported notifications (filtered by year/status)
        $recentlyReported = (clone $notificationsQuery)
            ->where('status', 'Reported')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Available years for dropdown
        $years = DataBreachNotification::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Available statuses for dropdown
        $statuses = DataBreachNotification::select('status')
            ->distinct()
            ->pluck('status');

        return view('databreach.overview_databreach', compact(
            'totalNotifications',
            'totalReported',
            'totalMandatory',
            'totalVoluntary',
            'causeCards',
            'recentlyReported',
            'years',
            'year',
            'statuses',
            'statusFilter'
        ));
    }

    // Handle Save as Draft (Enhanced to allow partial saving)
    public function save_as_draft(Request $request, $dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        // 1. CHANGED: All 'required' rules are now 'nullable' so incomplete forms can actually be saved as drafts.
        $data = $request->validate([
            'dbn_number'                    => 'nullable|string|max:100',
            'pic'                           => 'nullable|string|max:255',
            'email'                         => 'nullable|email|max:255',
            'representative'                => 'nullable|string|max:255',
            'representative_email_address'  => 'nullable|email|max:255',
            'date_occurrence'               => 'nullable|date',
            'date_discovery'                => 'nullable|date',
            'date_notification'             => 'nullable|date',
            'brief_summary'                 => 'nullable|string',
            'notification_type'             => 'nullable|string|max:255',
            'notification_type_description'   => 'nullable|array',
            'notification_type_description.*' => 'string|max:255',
            'sector_name'                   => 'nullable|string|max:255',
            'subsector_name'                => 'nullable|string|max:255',
            'timeliness'                    => 'nullable|string|max:255',
            'general_cause'                 => 'nullable|string|max:255',
            'specific_cause'                => 'nullable|string|max:255',
            'general_incident'              => 'nullable|string|max:255',
            'with_request'                  => 'nullable|in:Yes,No',
            'how_breach_occured'            => 'nullable|string',
            'chronology'                    => 'nullable|string',
            'num_records'                   => 'nullable|integer',
            'hundred_plus'                  => 'nullable|boolean',
            'num_records_provide_details'   => 'nullable|string',
            'description_nature'            => 'nullable|string',
            'likely_consequences'           => 'nullable|string',
            'spi'                           => 'nullable|string|max:255',
            'other_info'                    => 'nullable|string',
            'measures_to_address'           => 'nullable|string',
            'measures_to_secure'            => 'nullable|string',
            'actions_to_mitigate'           => 'nullable|string',
            'actions_to_inform'             => 'nullable|string',
            'actions_to_prevent'            => 'nullable|string',
            'record_type'                   => 'nullable|string|max:255',
            'data_subjects'                 => 'nullable|string|max:255',
        ]);

        $dpoRoleId = Role::where('name', 'DPO')->value('id');
        $dpo = User::where('role', $dpoRoleId)->first(); 

        // Prepare DPO display
        $data['dpo'] = implode(' | ', array_filter([
            $dpo->name ?? null,
            $dpo->email ?? null,
            $dpo->contact_number ?? null,
        ]));

        // If 'notification_type_description' is an array, ensure your DataBreachNotification model 
        // has it casted to 'array' or 'json', otherwise encode it here:
        if (isset($data['notification_type_description'])) {
            $data['notification_type_description'] = json_encode($data['notification_type_description']);
        }

        // Override/ensure the status remains or is set to 'Draft'
        $data['status'] = 'Draft';

        // Update the notification with the partially filled data
        $notification->update($data);

        return redirect()
            ->route('databreach.index')
            ->with('success', 'Incident report saved as draft successfully.');
    }

    // Handle Create
    public function create()
    {
        return view('databreach.create_databreach');
    }

    // Handle Store
    public function store(Request $request)
    {
        // Validate user inputs
        $data = $request->validate([
            'sender_fullname'       => 'required|string|max:255',
            'sender_email'          => 'required|email|max:255',
            'date_occurrence'       => 'required|date',
            'date_discovery'        => 'required|date',
            'date_notification'     => 'required|date',
            'pic'                   => 'required|string|max:255',
            'brief_summary'         => 'required|string',
            'time_countdown'        => 'nullable|integer',
            'evaluation_time_countdown' => 'nullable|integer',
        ]);

        try {
            // Start a database transaction. 
            DB::beginTransaction();

            $data['status'] = 'For Assessment';
            $data['hundred_plus'] = 0;
            $data['email'] = $data['sender_email'];
            
            // Ensure time_countdown always has a default value
            $data['time_countdown'] = $request->input('time_countdown', 24); 
            $data['evaluation_time_countdown'] = $request->input('evaluation_time_countdown', 48);

            $year = now()->year;
            
            // FIX: Added lockForUpdate() to prevent concurrent requests from generating duplicates
            $lastNumber = DatabreachForAssessment::whereYear('created_at', $year)
                ->orderBy('dbn_id', 'desc')
                ->lockForUpdate() 
                ->value('dbn_number');

            if ($lastNumber) {
                // Extract the number after the last '-'
                $lastSeq = (int) substr($lastNumber, strrpos($lastNumber, '-') + 1);
                $nextSeq = $lastSeq + 1;
            } else {
                $nextSeq = 1;
            }

            // Format to at least 2 digits (e.g., 01, 02... 99, 100)
            $formattedSeq = str_pad($nextSeq, 2, '0', STR_PAD_LEFT);
            $data['dbn_number'] = "CDA-DBN-{$year}-{$formattedSeq}";

            // Insert into the first table
            DatabreachForAssessment::create($data);
        
            // Insert into the second table using Eloquent
            DataBreachNotification::create([
                'dbn_number'            => $data['dbn_number'],
                'sender_fullname'       => $data['sender_fullname'],
                'sender_email'          => $data['sender_email'],
                'date_occurrence'       => $data['date_occurrence'],
                'date_discovery'        => $data['date_discovery'],
                'date_notification'     => $data['date_notification'],
                'pic'                   => $data['pic'],
                'brief_summary'         => $data['brief_summary'],
                'status'                => $data['status'],
                'time_countdown'        => $data['time_countdown'],
                'evaluation_time_countdown' => $data['evaluation_time_countdown'],
            ]);

            // Save the data to the database and release the lock
            DB::commit();

            // Attempt to send the email
            Mail::to($data['sender_email'])->send(new IncidentSubmitted($data));
            
            return redirect()->back()->with('success', "Incident report submitted successfully. Status: For Assessment.");

        } catch (Exception $e) {
            // Roll back the database changes and release the lock if an error occurs
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Incident Report Error: ' . $e->getMessage());
            
            // Redirect back with the exact error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the report: ' . $e->getMessage());
        }
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
        // 1. Find the DPO purely from the database (Strictly independent of logged-in user)
        // First checks via Spatie Permission trait, then falls back to column check
        $dpoDetails = User::role('DPO')->first();
        if (!$dpoDetails) {
            $dpoRoleId = Role::where('name', 'DPO')->value('id');
            $dpoDetails = User::where('role', $dpoRoleId)->orWhere('role', 'DPO')->first();
        }

        // 2. Resolve Representative logic
        $email = auth()->user()->email;
        $loggedInUser = DatabreachTeam::where('email', $email)->first();
        if (!$loggedInUser) {
            $loggedInUser = User::where('email', $email)->first();
        }

        $representativeName = $loggedInUser instanceof DatabreachTeam
            ? $loggedInUser->firstname . ' ' . $loggedInUser->lastname
            : ($loggedInUser->name ?? 'Unknown User');

        // 3. Prepare Notification Model
        $notification = DataBreachNotification::findOrFail($dbn_id);

        if (is_string($notification->notification_type_description)) {
            $notification->notification_type_description = json_decode($notification->notification_type_description, true);
        }

        $region = $notification->pic;
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();
        $notification->team_email = $team ? $team->email : null;

        return view('databreach.assess_databreach', compact('notification', 'dpoDetails', 'loggedInUser', 'representativeName'));
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

        // ---------------- VALIDATION ----------------
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
            'general_cause'                     => 'required|string|max:255',
            'specific_cause'                    => 'required|string|max:255',
            'general_incident'                  => 'required|string|max:255',
            'with_request'                      => 'required|in:Yes,No',
            'how_breach_occured'                => 'required|string',
            'chronology'                        => 'required|string',
            'num_records'                       => 'required|string',
            'hundred_plus'                      => 'nullable|boolean',
            'num_records_provide_details'       => 'required|string',
            'description_nature'                => 'required|string',
            'likely_consequences'               => 'required|string',
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

        if (!empty($data['notification_type_description'])) {
            $data['notification_type_description'] = json_encode($data['notification_type_description']);
        }

        // --- NEW CALCULATION LOGIC ---
        if ($notification->status === 'For Assessment') {
            $deadline = Carbon::parse($notification->created_at)->addHours(24);
            $now = now();
            
            if ($now->lessThan($deadline)) {
                $data['time_countdown'] = $now->diffInSeconds($deadline);
            } else {
                $data['time_countdown'] = 0;
            }
        }

        // 1. Fetch DPO independently to store assignment record inside DB
        $dpo = User::role('DPO')->first(); 
        if (!$dpo) {
            $dpoRoleId = Role::where('name', 'DPO')->value('id');
            $dpo = User::where('role', $dpoRoleId)->orWhere('role', 'DPO')->first();
        }

        $dpoFormattedString = implode(' | ', array_filter([
            $dpo->name ?? null,
            $dpo->email ?? null,
            $dpo->contact_number ?? null,
        ]));
        
        $data['dpo'] = $dpoFormattedString;
        $data['DPO'] = $dpoFormattedString; // Covers both exact/lowercase column matches

        $data['status'] = 'For Evaluation';

        $notification->update($data);

        // 2. Collect all DPO emails and dispatch evaluation securely
        $dpoEmails = User::role('DPO')->pluck('email')->filter()->toArray();
        if (empty($dpoEmails)) {
            $dpoRoleId = Role::where('name', 'DPO')->value('id');
            $dpoEmails = User::where('role', $dpoRoleId)->orWhere('role', 'DPO')->pluck('email')->filter()->toArray();
        }

        foreach ($dpoEmails as $dpoEmail) {
            Mail::to($dpoEmail)->send(new IncidentForEvaluation($notification));
        }

        return redirect()
            ->route('databreach.index')
            ->with('success', 'Incident report assessed successfully. Status: For Evaluation by DPO.');
    }

    // Handle Evaluation
    public function evaluate($dbn_id)
    {
        // 1. Find the DPO purely from the database (Strictly independent of logged-in user)
        $dpoDetails = User::role('DPO')->first();
        if (!$dpoDetails) {
            $dpoRoleId = Role::where('name', 'DPO')->value('id');
            $dpoDetails = User::where('role', $dpoRoleId)->orWhere('role', 'DPO')->first();
        }

        $notification = DataBreachNotification::findOrFail($dbn_id);

        // Decode JSON into array for Blade display
        if (is_string($notification->notification_type_description)) {
            $notification->notification_type_description = json_decode($notification->notification_type_description, true);
        }

        $region = $notification->pic;
        $team = DatabreachTeam::where('region', 'like', '%' . $region . '%')->first();

        $notification->team_email = $team ? $team->email : null;

        // Ensure we pass dpoRoleId if the blade still references it, along with the fixed dpoDetails
        $dpoRoleId = Role::where('name', 'DPO')->value('id'); 
        
        return view('databreach.evaluate_databreach', compact('notification', 'dpoRoleId', 'dpoDetails'));
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

        $request->merge([
            'status' => $request->has('status') ? 'Reported' : 'Not Reported'
        ]);

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
            'general_cause'                     => 'required|string|max:255',
            'specific_cause'                    => 'required|string|max:255',
            'general_incident'                  => 'required|string|max:255',
            'with_request'                      => 'required|in:Yes,No',
            'how_breach_occured'                => 'required|string',
            'chronology'                        => 'required|string',
            'num_records'                       => 'required|string',
            'hundred_plus'                      => 'nullable|boolean',
            'num_records_provide_details'       => 'required|string',
            'description_nature'                => 'required|string',
            'likely_consequences'               => 'required|string',
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

        try {
            DB::beginTransaction();

            if (isset($data['notification_type_description']) && is_array($data['notification_type_description'])) {
                $data['notification_type_description'] = json_encode($data['notification_type_description']);
            }

            // --- NEW 48-HOUR CALCULATION LOGIC ---
            if ($notification->status === 'For Evaluation') {
                $deadline = Carbon::parse($notification->updated_at)->addHours(48);
                $now = now();
                
                if ($now->lessThan($deadline)) {
                    $data['evaluation_time_countdown'] = $now->diffInSeconds($deadline);
                } else {
                    $data['evaluation_time_countdown'] = 0; // Time expired
                }
            }

            // 1. Fetch DPO independently to store assignment record inside DB securely
            $dpo = User::role('DPO')->first(); 
            if (!$dpo) {
                $dpoRoleId = Role::where('name', 'DPO')->value('id');
                $dpo = User::where('role', $dpoRoleId)->orWhere('role', 'DPO')->first();
            }

            $dpoFormattedString = implode(' | ', array_filter([
                $dpo->name ?? null,
                $dpo->email ?? null,
                $dpo->contact_number ?? null,
            ]));
            
            $data['dpo'] = $dpoFormattedString;
            $data['DPO'] = $dpoFormattedString; // Covers both exact/lowercase column matches
        
            $data['status'] = 'For Reporting to NPC';
            
            // Execute the update
            $notification->update($data);

            // Send emails to the team
            $teamEmails = DatabreachTeam::whereNotNull('email')->pluck('email');
            foreach ($teamEmails as $email) {
                Mail::to($email)->send(new IncidentEvaluated($data));
            }

            DB::commit();

            return redirect()->route('databreach.index')
                ->with('success', 'Incident report evaluated successfully. Status: For reporting to the NPC by the DPO.');

        } catch (\Exception $e) { // Make sure Exception is fully qualified with \
            DB::rollBack();
            \Log::error('Evaluation Error: ' . $e->getMessage()); // Ensure Log uses \ 
            
            // This will redirect back and show you the actual database or email error!
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving: ' . $e->getMessage());
        }
    }

    public function report_to_npc($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        $notification->status = 'Reported';
        $notification->save();

        return redirect()->route('databreach.index')
            ->with('success', 'Incident report has been reported to the NPC successfully.');
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
