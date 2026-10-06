<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ReassignedTicket;

class ReassignedTicketsController extends Controller
{
    // Display Re-assigned Tickets with only status from tickets table
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = DB::table('reassigned_tickets')
            ->leftJoin('tickets', 'reassigned_tickets.ticket_number', '=', 'tickets.ticket_number')
            ->select(
                'reassigned_tickets.*',
                'tickets.status as status',
                'tickets.it_area as it_area'
            )
            ->orderBy('reassigned_tickets.re_assigned_at', 'desc');

        if ($user->hasRole('Super Admin')) {
            // Super Admin: sees ALL re-assigned tickets — no filter applied.

        } elseif ($user->hasRole('ICTS Admin')) {
            // ICTS Admin: sees all re-assigned tickets within their region (it_area).
            if (! empty($user->region)) {
                $query->where('tickets.it_area', $user->region);
            }
        } else {
            // All other roles: only see tickets they personally re-assigned.
            $query->where('reassigned_tickets.assigned_by', $user->name);
        }

        // Apply search query filter
        if ($request->filled('search_query')) {
            $search = trim($request->input('search_query'));
            $query->where(function ($q) use ($search) {
                $q->where('reassigned_tickets.ticket_number', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.requested_by', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.request', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.assigned_by', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.previous_assigned', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.re_assigned_to', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.notes', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.priority', 'like', "%{$search}%")
                    ->orWhere('reassigned_tickets.status', 'like', "%{$search}%")
                    ->orWhere('tickets.status', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(10)->appends($request->only('search_query'));

        return view('tickets.reassigned_tickets', compact('tickets'));
    }
}

