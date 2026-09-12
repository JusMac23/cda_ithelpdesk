<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketsOverviewController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\AssignedToMeController;
use App\Http\Controllers\ReassignedTicketsController;
use App\Http\Controllers\MyRequestedTicketsController;
use App\Http\Controllers\CreateTicketPublicController;
use App\Http\Controllers\CreateTicketPrivateController;
use App\Http\Controllers\UploadClientSignatureController;
use App\Http\Controllers\UploadPersonnelSignatureController;
use App\Http\Controllers\GenerateTSARController;
use App\Http\Controllers\TechnicalPersonnelController;
use App\Http\Controllers\TechnicalServicesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CreateIncidentReportController;
use App\Http\Controllers\DataBreachReportsController;
use App\Http\Controllers\DatabreachPerRegionController;
use App\Http\Controllers\GenerateDocsController;
use App\Http\Controllers\DatabreachTeamController;
use App\Http\Controllers\Auth\OAuthGoogleController;
use App\Http\Controllers\Auth\OAuthAuthentikController;

Route::get('/', function () {
    return view('welcome');
});

// Social logins
Route::middleware('web')->group(function () {
    // Authentik OAuth
    Route::get('/auth/authentik', [OAuthAuthentikController::class, 'redirectToAuthentik'])->name('auth.authentik');
    Route::get('/auth/authentik/callback', [OAuthAuthentikController::class, 'handleAuthentikCallback']);

    // Google OAuth
    Route::get('/auth/google', [OAuthGoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [OAuthGoogleController::class, 'handleGoogleCallback']);
});

// Create Ticket (public)
Route::get('/create_ticket', [CreateTicketPublicController::class, 'showForm'])->name('tickets.create');
Route::post('/create_ticket', [CreateTicketPublicController::class, 'store'])->name('tickets.store.client');

// Create Incident Report (public)
Route::get('/create_incident', [CreateIncidentReportController::class, 'create'])->name('incident.create');
Route::post('/create_incident', [CreateIncidentReportController::class, 'store'])->name('incident.store');

// Client Signature Routes
Route::get('/tickets/{ticket_id}/client-signature', [UploadClientSignatureController::class, 'showSignatureForm'])->name('tickets.client_signature');
Route::post('/tickets/{ticket_id}/client-signature', [UploadClientSignatureController::class, 'saveSignature'])->name('tickets.saveClientSignature');

// Personnel Signature Routes
Route::get('/tickets/{ticket_id}/personnel-signature', [UploadPersonnelSignatureController::class, 'showSignatureForm'])->name('tickets.personnel_signature');
Route::post('/tickets/{ticket_id}/personnel-signature', [UploadPersonnelSignatureController::class, 'saveSignature'])->name('tickets.savePersonnelSignature');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:view_profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:edit_profile|update_password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('permission:delete_profile');
    Route::post('/profile/image', [ProfileController::class, 'updatePhoto'])->name('profile.upload_image');

    // Tickets Overview
    Route::get('/tickets/overview_tickets', [TicketsOverviewController::class, 'index'])->name('overview_tickets.index')->middleware('permission:view_overview_tickets');
    
    // Ticket Management
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index')->middleware('permission:view_all_tickets');
    Route::post('/tickets/store', [CreateTicketPrivateController::class, 'store'])->name('tickets.store')->middleware('permission:create_ticket');
    Route::post('/tickets/re_assign', [TicketsController::class, 're_assign'])->name('tickets.re_assign')->middleware('permission:reassign_ticket');
    Route::put('/tickets/{ticket_id}', [TicketsController::class, 'update'])->name('tickets.update')->middleware('permission:update_status_ticket');
    Route::delete('/tickets/{ticket_id}', [TicketsController::class, 'destroy'])->name('tickets.destroy')->middleware('permission:delete_ticket');
    Route::get('/tickets/{ticket_id}/generate-tsar', [GenerateTSARController::class, 'generateTSAR'])->name('tickets.generateTSAR')->middleware('permission:generate_tsar');

    // Export Tickets Overview to CSV
    Route::get('/dashboard/export-pdf', [TicketsOverviewController::class, 'exportPdf'])->name('tickets.export_pdf')->middleware('permission:generate_report');

    // Tickets Assigned per Technical Personnel
    Route::get('/tickets/assignedtome_tickets', [AssignedToMeController::class, 'index'])->name('assignedtome_tickets.index')->middleware('permission:view_assignedtome_tickets');
    Route::get('/tickets/reassigned_tickets', [ReassignedTicketsController::class, 'index'])->name('reassigned_tickets.index')->middleware('permission:view_reassigned_tickets');

    // Tickets Request per Client
    Route::get('/tickets/myrequested_tickets', [MyRequestedTicketsController::class, 'index'])->name('myrequested_tickets.index')->middleware('permission:view_myrequested_tickets');
    Route::post('/tickets/myrequested/store', [MyRequestedTicketsController::class, 'store'])->name('tickets.myrequested.store')->middleware('permission:create_myrequested_tickets');

    // Data Breach Notification
    Route::get('/databreach', [DataBreachReportsController::class, 'index'])->name('databreach.index')->middleware('permission:view_all_databreach');
    Route::get('/overview_databreach', [DataBreachReportsController::class, 'overview'])->name('databreach.overview')->middleware('permission:view_overview_databreach');
    Route::get('/create_databreach', [DataBreachReportsController::class, 'create'])->name('databreach.create')->middleware('permission:create_databreach');
    Route::post('/create_databreach', [DataBreachReportsController::class, 'store'])->name('databreach.store')->middleware('permission:create_databreach');
    Route::get('/databreach/{dbn_id}', [DataBreachReportsController::class, 'show'])->name('databreach.show')->middleware('permission:view_databreach');
    Route::put('/databreach/{dbn_id}/draft', [DataBreachReportsController::class, 'save_as_draft'])->name('databreach.save_draft');
    Route::put('/databreach/{dbn_id}', [DataBreachReportsController::class, 'update'])->name('databreach.update');
    Route::get('/assess_databreach/{dbn_id}/assess', [DataBreachReportsController::class, 'assess'])->name('databreach.assess')->middleware('permission:assess_databreach');
    Route::get('/evaluate_databreach/{dbn_id}/evaluate', [DataBreachReportsController::class, 'evaluate'])->name('databreach.evaluate')->middleware('permission:evaluate_databreach');
    Route::patch('/databreach/{dbn_id}/report', [DataBreachReportsController::class, 'report_to_npc'])->name('databreach.report_to_npc')->middleware('permission:report_databreach');
    Route::get('/get-dbrt-email/assess/{region}', [DataBreachReportsController::class, 'assess_getDbrtEmail']);
    Route::get('/get-dbrt-email/evaluate/{region}', [DataBreachReportsController::class, 'evaluate_getDbrtEmail']);
    Route::put('/assess_databreach/{dbn_id}', [DataBreachReportsController::class, 'update_assessment'])->name('databreach.update_assessment')->middleware('permission:assess_databreach');
    Route::put('/evaluate_databreach/{dbn_id}', [DataBreachReportsController::class, 'update_evaluation'])->name('databreach.update_evaluation')->middleware('permission:evaluate_databreach');
    Route::delete('/databreach/{dbn_id}', [DataBreachReportsController::class, 'destroy'])->name('databreach.destroy')->middleware('permission:delete_databreach');

    // Generate Data Breach Report
    Route::get('/databreach/{id}/generate-pdf', [GenerateDocsController::class, 'generatePdf'])->name('databreach.generatePdf');

    // Create Data Breach Notification Per Region
    Route::get('/per_region_databreach', [DatabreachPerRegionController::class, 'index'])->name('databreach.per_region_databreach');

    // Data Breach Response Team (DBRT) Management
    Route::get('/team_databreach', [DatabreachTeamController::class, 'index'])->name('databreach.team_databreach')->middleware('permission:view_dbrt');
    Route::post('/team_databreach', [DatabreachTeamController::class, 'store'])->name('databreach.team_databreach.store')->middleware('permission:create_dbrt');
    Route::put('/databreach/team_databreach/{dbrt_id}', [DatabreachTeamController::class, 'update'])->name('databreach.team_databreach.update')->middleware('permission:edit_dbrt');
    Route::delete('/team_databreach/{dbrt_id}', [DatabreachTeamController::class, 'destroy'])->name('databreach.team_databreach.destroy')->middleware('permission:delete_dbrt');

    // Technical Personnel
    Route::get('/tech_personnel', [TechnicalPersonnelController::class, 'index'])->name('tech_personnel.index')->middleware('permission:view_technical_personnel');
    Route::post('/tech_personnel', [TechnicalPersonnelController::class, 'store'])->name('tech_personnel.store')->middleware('permission:create_technical_personnel');
    Route::put('/tech_personnel/{id}', [TechnicalPersonnelController::class, 'update'])->name('tech_personnel.update')->middleware('permission:edit_technical_personnel');
    Route::delete('/tech_personnel/{id}', [TechnicalPersonnelController::class, 'destroy'])->name('tech_personnel.destroy')->middleware('permission:delete_technical_personnel');

    // Technical Services
    Route::get('/tech_services', [TechnicalServicesController::class, 'index'])->name('tech_services.index')->middleware('permission:view_technical_services');
    Route::post('/tech_services', [TechnicalServicesController::class, 'store'])->name('tech_services.store')->middleware('permission:create_technical_services');
    Route::put('/tech_services/{id}', [TechnicalServicesController::class, 'update'])->name('tech_services.update')->middleware('permission:edit_technical_services');
    Route::delete('/tech_services/{id}', [TechnicalServicesController::class, 'destroy'])->name('tech_services.destroy')->middleware('permission:delete_technical_services');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index')->middleware('permission:view_tech_users');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store')->middleware('permission:create_tech_users');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update')->middleware('permission:edit_tech_users');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_tech_users');

    // Roles
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index')->middleware('permission:view_roles');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store')->middleware('permission:create_roles');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('roles.update')->middleware('permission:edit_roles');
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete_roles');

});

// Auth routes
require __DIR__.'/auth.php';