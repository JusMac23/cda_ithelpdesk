<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatabreachForAssessment;
use App\Models\DataBreachNotification;
use App\Models\DataBreachTeam;
use App\Mail\IncidentSubmitted; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Mail;

class CreateIncidentReportController extends Controller
{
    // Handle Create
    public function create()
    {
        return view('databreach.create_incident');
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
        
        return redirect()->back()->with('success', "Incident Report submitted successfully with DBN Number: {$data['dbn_number']} and status: For Assessment.");

    }
}
