<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Tickets;

class DashboardController extends Controller
{
    public function index()
    {
        // Total ticket counts
        $total = Tickets::count();
        $pending = Tickets::whereIn('status', ['Pending', 'Pending/Re-Assigned'])->count();
        $resolved = Tickets::where('status', 'Resolved')->count();

        // Count overdue (older than 3 days and not resolved)
        $overdue = Tickets::whereIn('status', ['Pending', 'Pending/Re-Assigned'])
            ->whereDate('date_created', '<', now()->subDays(3))
            ->count();

        // Group by IT Area
        $byItArea = DB::table('tickets')
            ->select('it_area', DB::raw('count(*) as total'))
            ->groupBy('it_area')
            ->get();

        // Group by IT Personnel
        $byItPersonnel = DB::table('tickets')
            ->select('it_personnel', DB::raw('COUNT(*) as total'))
            ->groupBy('it_personnel')
            ->get();

        // Group by Service Type
        $byService = Tickets::select('service')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('service')
            ->get();

        // Recently Resolved (latest 5)
        $recentlyResolved = Tickets::where('status', 'Resolved')
            ->orderByDesc('date_resolved')
            ->limit(5)
            ->get();

        // Overdue Tickets per Personnel (grouped)
        $overdueTickets = Tickets::where('status', '!=', 'Resolved')
            ->whereDate('date_created', '<', Carbon::now()->subDays(3))
            ->orderBy('it_personnel')
            ->get()
            ->groupBy('it_personnel');

        return view('dashboard', compact(
            'total',
            'pending',
            'resolved',
            'overdue',
            'byItArea',
            'byItPersonnel',
            'byService',
            'recentlyResolved',
            'overdueTickets'
        ));
    }
}

