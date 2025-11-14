<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataBreachNotification;
use App\Models\DatabreachForAssessment;
use App\Models\DatabreachTeam;
use App\Mail\IncidentSubmitted; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Mail;

class DatabreachPerRegionController extends Controller
{
    // Handle Provide List
   public function index(Request $request)
    {
        $status = $request->input('status');
        $userEmail = Auth::user()->email;

        // Get the user's region from the DatabreachTeam table
        $team = DatabreachTeam::where('email', $userEmail)->first();
        $userRegion = $team ? $team->region : null;

        // Fetch notifications assigned to this user and their region
        $query = DataBreachNotification::query()
            ->where('email', $userEmail)
            ->where('pic', $userRegion); 

        // Apply status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Paginate results
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['status' => $status]);

        return view('databreach.per_region_databreach', compact('notifications'));
    }

    
}
