<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tickets;
use App\Models\ClientFeedback;

class ClientFeedbackController extends Controller
{
    /**
     * Show the Client Satisfaction Measurement (CSM) feedback form.
     */
    public function showFeedbackForm($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);
        $existingFeedback = null;
        try {
            $existingFeedback = ClientFeedback::where('ticket_id', $ticket_id)->first();
        } catch (\Throwable $e) {
            $existingFeedback = null;
        }

        // Automatically generated date (Asia/Manila timezone)
        $autoDate = Carbon::now('Asia/Manila')->format('F d, Y');
        $autoDateInput = Carbon::now('Asia/Manila')->format('Y-m-d');

        // Automatically generated region from the ticket's assigned IT area / region
        $autoRegion = $ticket->it_area ?? 'CDA HO';

        return view('tickets.feedback', compact(
            'ticket',
            'existingFeedback',
            'autoDate',
            'autoDateInput',
            'autoRegion'
        ));
    }

    /**
     * Save the submitted Client Satisfaction Measurement (CSM) feedback.
     */
    public function saveFeedback(Request $request, $ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);

        $validated = $request->validate([
            'client_type'   => 'nullable|string',
            'feedback_date' => 'nullable|string',
            'sex'           => 'nullable|string|in:Male,Female',
            'age'           => 'nullable|string|max:10',
            'region'        => 'nullable|string|max:255',
            'services'      => 'nullable|array',
            'services.*'    => 'string',
            'service_other' => 'nullable|string|max:255',
            'cc1'           => 'nullable|string',
            'cc2'           => 'nullable|string',
            'cc3'           => 'nullable|string',
            'sqd1'          => 'nullable|string',
            'sqd2'          => 'nullable|string',
            'sqd3'          => 'nullable|string',
            'sqd4'          => 'nullable|string',
            'sqd5'          => 'nullable|string',
            'sqd6'          => 'nullable|string',
            'sqd7'          => 'nullable|string',
            'sqd8'          => 'nullable|string',
            'suggestions'   => 'nullable|string|max:2000',
            'email'         => 'nullable|email|max:255',
        ]);

        // Ensure date and region fallback to ticket defaults if not sent or disabled
        if (empty($validated['feedback_date'])) {
            $validated['feedback_date'] = Carbon::now('Asia/Manila')->format('F d, Y');
        }
        if (empty($validated['region'])) {
            $validated['region'] = $ticket->it_area ?? 'CDA HO';
        }

        $validated['ticket_id'] = $ticket->ticket_id;

        ClientFeedback::updateOrCreate(
            ['ticket_id' => $ticket->ticket_id],
            $validated
        );

        return redirect()->route('tickets.feedback', $ticket->ticket_id)
            ->with('success', 'Thank you! Your feedback has been successfully submitted.');
    }
}
