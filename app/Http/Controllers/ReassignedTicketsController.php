<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ReassignedTicket;

class ReassignedTicketsController extends Controller
{
    // Display Re-assigned Tickets with only status from tickets table
    public function index()
    {
        $tickets = DB::table('reassigned_tickets')
            ->leftJoin('tickets', 'reassigned_tickets.ticket_number', '=', 'tickets.ticket_number')
            ->select(
                'reassigned_tickets.*',
                'tickets.status as status' 
            )
            ->orderBy('reassigned_tickets.re_assigned_at', 'desc')
            ->paginate(10);

        return view('tickets.reassigned_tickets', compact('tickets'));
    }
}

