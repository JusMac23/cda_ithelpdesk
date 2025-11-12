<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatabreachTeam;

class CreateDataBreachTeamController extends Controller
{
    // Display DBRT Team List
    public function index()
    {
        $dbrtTeam = DatabreachTeam::orderBy('region', 'asc')->paginate(20);
        return view('databreach.team_databreach', compact('dbrtTeam'));
    }

    // Add New Data Breach Team Member
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:155',
            'middle_initial' => 'nullable|string|max:5',
            'lastname' => 'required|string|max:155',
            'email' => 'required|email|unique:databreach_dbrt_team,email',
            'region' => 'required|string|max:255',
        ]);

        DatabreachTeam::create([
            'firstname' => $request->firstname,
            'middle_initial' => $request->middle_initial,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'region' => $request->region,
        ]);

        return redirect()->route('databreach.team_databreach')
                         ->with('success', 'DBRT member added successfully.');
    }

    // Update Data Breach Team Member
    public function update(Request $request, $dbrt_id)
    {
        $dbrtTeam = DatabreachTeam::findOrFail($dbrt_id);

        $request->validate([
            'firstname' => 'required|string|max:155',
            'middle_initial' => 'nullable|string|max:5',
            'lastname' => 'required|string|max:155',
            'email' => 'required|email|unique:databreach_dbrt_team,email,' . $dbrtTeam->dbrt_id . ',dbrt_id',
            'region' => 'required|string|max:255',
        ]);

        $dbrtTeam->update($request->only([
            'firstname',
            'middle_initial',
            'lastname',
            'email',
            'region'
        ]));

        return redirect()->route('databreach.team_databreach')
                         ->with('success', 'DBRT member updated successfully.');
    }

    // Delete Data Breach Team Member
    public function destroy($dbrt_id)
    {
        $dbrtTeam = DatabreachTeam::findOrFail($dbrt_id);
        $dbrtTeam->delete();

        return redirect()->route('databreach.team_databreach')
                         ->with('success', 'DBRT member deleted successfully.');
    }
}
