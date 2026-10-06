@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="CDA-ICT Helpdesk System - Rapid Response, Centralized IT Support & Secure Data Breach Incident Reporting for the Cooperative Development Authority">

    <title>CDA-ICT Helpdesk - Official Portal</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-cyber-gradient animate-gradient text-slate-100 font-['Inter',sans-serif] antialiased leading-relaxed overflow-x-hidden selection:bg-blue-500 selection:text-white">

<!-- Header Navigation Bar (Synchronized with create_ticket and create_incident) -->
<header class="sticky top-0 z-50 bg-slate-900/75 backdrop-blur-xl border-b border-white/10 shadow-[0_4px_30px_rgba(0,0,0,0.1)]">
    <div class="h-[3px] bg-gradient-to-r from-blue-500 via-violet-500 to-red-500"></div>
    <div class="w-full max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ url('/') }}" aria-label="Home" class="group flex items-center gap-3 text-2xl font-extrabold text-slate-50 tracking-tight transition-colors focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Seal" loading="lazy" class="w-11 h-11 object-contain drop-shadow-[0_0_8px_rgba(255,255,255,0.2)] transition-transform duration-300 ease-out group-hover:scale-110 group-hover:-rotate-6 group-hover:drop-shadow-[0_0_12px_rgba(255,255,255,0.4)]">
            <span>CDA-ICT Helpdesk</span>
        </a>

        <nav aria-label="Main Navigation">
            <ul class="flex items-center gap-4 text-[0.95rem] font-semibold list-none m-0 p-0">
                @auth
                    <li>
                        <a href="{{ url('/tickets/overview_tickets') }}" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-slate-50 border border-transparent transition-all duration-300 ease-out hover:bg-blue-500/10 hover:border-blue-500/30 hover:shadow-[0_4px_12px_rgba(59,130,246,0.15)] focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Tickets Overview">
                            <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">table_chart_view</span> 
                            <span class="nav-text hidden md:inline">Tickets Overview</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-red-300 border border-transparent transition-all duration-300 ease-out hover:text-slate-50 hover:bg-red-500/15 hover:border-red-500/30 hover:shadow-[0_4px_12px_rgba(239,68,68,0.15)] cursor-pointer focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Logout">
                                <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">logout</span> 
                                <span class="nav-text hidden md:inline">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-slate-50 border border-transparent transition-all duration-300 ease-out hover:bg-blue-500/10 hover:border-blue-500/30 hover:shadow-[0_4px_12px_rgba(59,130,246,0.15)] focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Login">
                            <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">login</span> 
                            <span class="nav-text hidden md:inline">Login</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<main class="grow">
    
    {{-- Hero Section --}}
    <section class="hero relative w-full min-h-[85vh] flex items-center justify-center bg-cover bg-center bg-no-repeat bg-fixed overflow-hidden" style="background-image: url('{{ asset('images/icthelpdesk.png') }}');" aria-labelledby="hero-heading">
        {{-- Subtle Multi-layer Cyber Gradient Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/65 to-slate-950/90 pointer-events-none z-[1]" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-900/20 via-transparent to-transparent pointer-events-none z-[1]"></div>

        <div class="relative z-10 w-full max-w-5xl mx-auto text-center px-4 sm:px-6 py-16 md:py-24">
            
            {{-- Official Agency Pill Badge --}}
            <div class="inline-flex items-center gap-2.5 bg-slate-900/80 border border-blue-400/40 text-blue-300 px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-semibold tracking-wide mb-8 backdrop-blur-md shadow-lg shadow-blue-500/10 animate-fade-in-up">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span>Cooperative Development Authority • Official ICT Support Portal</span>
            </div>

            {{-- Main Title --}}
            <h1 id="hero-heading" class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-[1.1] tracking-tight text-white animate-fade-in-up [animation-delay:0.1s]">
                Rapid Response & Tracking for the <br>
                <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-purple-400 bg-clip-text text-transparent drop-shadow-md">
                    CDA-ICT Helpdesk System
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-10 leading-relaxed max-w-3xl mx-auto font-normal animate-fade-in-up [animation-delay:0.2s]">
                Centralized IT technical support and secure data breach reporting for all CDA Head Office and Regional units nationwide. Submit issues, track resolutions, and protect digital assets in one unified platform.
            </p>

            {{-- Primary Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-4 sm:gap-5 max-w-xl mx-auto animate-fade-in-up [animation-delay:0.3s]">
                <a href="{{ url('create_ticket') }}" 
                   class="group inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-xl shadow-blue-600/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300" 
                   aria-label="Submit Ticket">
                    <span class="material-symbols-outlined text-2xl transition-transform group-hover:rotate-12">confirmation_number</span>
                    <span>Submit Ticket</span>
                    <span class="material-symbols-outlined text-lg opacity-60 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>

                <a href="{{ url('create_incident') }}" 
                   class="group inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 border border-red-400/40 shadow-xl shadow-red-600/30 hover:shadow-red-500/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300" 
                   aria-label="Report Incident">
                    <span class="material-symbols-outlined text-2xl transition-transform group-hover:scale-110">shield_locked</span>
                    <span>Report Incident</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] uppercase font-extrabold bg-white/20 text-white tracking-wider">DBRS</span>
                </a>
            </div>

            {{-- Quick Links / Alternative Actions --}}
            <div class="mt-8 flex items-center justify-center gap-6 text-xs sm:text-sm text-slate-400 animate-fade-in-up [animation-delay:0.35s]">
                <a href="#services-heading" class="hover:text-blue-400 flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-base">explore</span> Explore Services
                </a>
                <span class="text-slate-600">•</span>
                <a href="#faq-heading" class="hover:text-blue-400 flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-base">help_outline</span> View FAQs
                </a>
                <span class="text-slate-600">•</span>
                <a href="{{ route('login') }}" class="hover:text-blue-400 flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-base">lock</span> Staff Login
                </a>
            </div>

        </div>
    </section>

    {{-- System Capabilities & Support Grid (4 Cards) --}}
    <section class="relative z-10 py-16 md:py-24 bg-transparent" aria-labelledby="services-heading">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6">
            
            {{-- Section Heading --}}
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 inline-block mb-3">
                    Capabilities & Support Services
                </span>
                <h2 id="services-heading" class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                    Comprehensive Technical Assistance
                </h2>
                <p class="text-sm sm:text-base text-slate-400 mt-3 font-normal">
                    Designed to ensure mission-critical uptime, reliable workspace collaboration, and resilient cybersecurity across all regional offices.
                </p>
            </div>

            {{-- 4 Responsive Service Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Card 1: Hardware & Peripherals --}}
                <article class="group relative rounded-3xl bg-slate-900/60 hover:bg-slate-900/90 border border-white/10 hover:border-emerald-500/40 p-6 sm:p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/10 flex flex-col justify-between">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-t-3xl"></div>
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">devices</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Hardware & Devices</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-4">
                            Troubleshooting for desktop PCs, laptops, all-in-one printers, network scanners, and peripheral equipment maintenance.
                        </p>
                    </div>
                    <ul class="space-y-2 pt-4 border-t border-white/5 text-xs text-slate-300 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-400 text-sm">check_circle</span> Printer & Scanner Setup
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-400 text-sm">check_circle</span> OS & Software Diagnostics
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-400 text-sm">check_circle</span> Preventive Maintenance
                        </li>
                    </ul>
                </article>

                {{-- Card 2: Security & DBRS --}}
                <article class="group relative rounded-3xl bg-slate-900/60 hover:bg-slate-900/90 border border-white/10 hover:border-red-500/40 p-6 sm:p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-red-500/10 flex flex-col justify-between">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-rose-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-t-3xl"></div>
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">shield_locked</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Data Breach Response</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-4">
                            Rapid containment protocol for security disruptions, compromised accounts, and privacy incident reporting under CDA-DBRS.
                        </p>
                    </div>
                    <ul class="space-y-2 pt-4 border-t border-white/5 text-xs text-slate-300 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-400 text-sm">check_circle</span> Threat Isolation & Lockdown
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-400 text-sm">check_circle</span> Formal DBRS Documentation
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-400 text-sm">check_circle</span> NPC Compliance Guidance
                        </li>
                    </ul>
                </article>
                
                {{-- Card 3: Core Infrastructure --}}
                <article class="group relative rounded-3xl bg-slate-900/60 hover:bg-slate-900/90 border border-white/10 hover:border-blue-500/40 p-6 sm:p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 flex flex-col justify-between">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-t-3xl"></div>
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">dns</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Website and System Applications Support</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-4">
                            Assistance with CDA website maintenance, content updates, and support for agency's system applications.
                        </p>
                    </div>
                    <ul class="space-y-2 pt-4 border-t border-white/5 text-xs text-slate-300 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-400 text-sm">check_circle</span> Website Content Management
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-400 text-sm">check_circle</span> CDA System Applications Support
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-400 text-sm">check_circle</span> Technical Documentation & Training
                        </li>
                    </ul>
                </article>

                {{-- Card 3: Workspace & Accounts --}}
                <article class="group relative rounded-3xl bg-slate-900/60 hover:bg-slate-900/90 border border-white/10 hover:border-purple-500/40 p-6 sm:p-7 backdrop-blur-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10 flex flex-col justify-between">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-t-3xl"></div>
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">account_circle</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Workspace & Accounts</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-4">
                            User account provisioning, CDA Workspace identity integration, official email resets, and multi-factor authentication setup.
                        </p>
                    </div>
                    <ul class="space-y-2 pt-4 border-t border-white/5 text-xs text-slate-300 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400 text-sm">check_circle</span> Google Workspace & Email
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400 text-sm">check_circle</span> Workspace Account Management
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400 text-sm">check_circle</span> Password & Access Recovery
                        </li>
                    </ul>
                </article>

            </div>
        </div>
    </section>

    {{-- How It Works Section (3 Steps) --}}
    <section class="relative z-10 py-12 md:py-20 bg-slate-950/40 border-y border-white/5">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 inline-block mb-3">
                    Support Workflow
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                    How the ICT Helpdesk Works
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 relative">
                {{-- Step 1 --}}
                <div class="relative p-6 sm:p-8 rounded-3xl bg-slate-900/60 border border-white/10 backdrop-blur-md">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-600/30">
                        1
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Submit Your Request</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Fill out the online ticket form with your equipment, problem description, priority level, and optional screenshots.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="relative p-6 sm:p-8 rounded-3xl bg-slate-900/60 border border-white/10 backdrop-blur-md">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-600/30">
                        2
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Automated Assignment</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        The system uses round-robin auto-routing to immediately assign the ticket to the designated ICT personnel in your region.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="relative p-6 sm:p-8 rounded-3xl bg-slate-900/60 border border-white/10 backdrop-blur-md">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-600/30">
                        3
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Resolution & Sign-Off</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Receive instant status updates via email, verify the resolved service, and confirm completion with your digital e-signature.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section (Modernized Accordion) --}}
    <section class="relative z-10 py-16 md:py-24" aria-labelledby="faq-heading">
        <div class="w-full max-w-4xl mx-auto px-4 sm:px-6">
            
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 inline-block mb-3">
                    Support Center
                </span>
                <h2 id="faq-heading" class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                    Frequently Asked Questions
                </h2>
                <p class="text-sm sm:text-base text-slate-400 mt-3 font-normal">
                    Quick answers to common questions about ticket tracking, accounts, and system access.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <details class="faq-item group bg-slate-900/60 backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:bg-slate-900/80">
                    <summary class="p-5 sm:p-6 text-base sm:text-lg font-bold text-white cursor-pointer list-none flex justify-between items-center select-none marker:content-none [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">help</span>
                            How can I track the status of my ticket submission?
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-2xl transition-transform duration-300 ease-out group-open:rotate-180 group-open:text-blue-400" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/5 space-y-2">
                        <p>Once you submit a request, you can easily track its real-time progress:</p>
                        <ol class="list-decimal pl-6 space-y-2 mt-2">
                            <li>Log in to your account with your authorized credentials.</li>
                            <li>Navigate to <strong>My Requested Tickets</strong> on the sidebar to view active assignments, technician notes, and status updates.</li>
                            <li>You will also receive automated email notifications whenever an IT specialist updates your ticket.</li>
                        </ol>
                    </div>
                </details>

                {{-- FAQ 2 --}}
                <details class="faq-item group bg-slate-900/60 backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:bg-slate-900/80">
                    <summary class="p-5 sm:p-6 text-base sm:text-lg font-bold text-white cursor-pointer list-none flex justify-between items-center select-none marker:content-none [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">lock</span>
                            Who is authorized to use this portal?
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-2xl transition-transform duration-300 ease-out group-open:rotate-180 group-open:text-blue-400" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/5 space-y-2">
                        <p>This ICT Helpdesk portal is exclusively for authorized Cooperative Development Authority (CDA) Personnel across all Regional Offices and the Head Office.</p>
                        <p>Public submissions require an official <code class="text-blue-400 bg-blue-950/60 px-2 py-0.5 rounded">@cda.gov.ph</code> email domain for ticket submission validation.</p>
                    </div>
                </details>

                {{-- FAQ 3 --}}
                <details class="faq-item group bg-slate-900/60 backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:bg-slate-900/80">
                    <summary class="p-5 sm:p-6 text-base sm:text-lg font-bold text-white cursor-pointer list-none flex justify-between items-center select-none marker:content-none [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">key</span>
                            What should I do if I can't log in?
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-2xl transition-transform duration-300 ease-out group-open:rotate-180 group-open:text-blue-400" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/5 space-y-2">
                        <ol class="list-decimal pl-6 space-y-2">
                            <li>Verify that you are using your registered CDA email address and correct password.</li>
                            <li>If forgotten, click the <strong>Forgot Password</strong> link on the login page to request a password reset email.</li>
                            <li>If your account is locked, contact your regional ICT administrator or email the <strong>ICT Division</strong> for manual unlock assistance.</li>
                        </ol>
                    </div>
                </details>

                {{-- FAQ 4 --}}
                <details class="faq-item group bg-slate-900/60 backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:bg-slate-900/80">
                    <summary class="p-5 sm:p-6 text-base sm:text-lg font-bold text-white cursor-pointer list-none flex justify-between items-center select-none marker:content-none [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">videocam</span>
                            How do I request a Zoom link or video conference?
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-2xl transition-transform duration-300 ease-out group-open:rotate-180 group-open:text-blue-400" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/5 space-y-2">
                        <ol class="list-decimal pl-6 space-y-2">
                            <li>Submit an official scheduling request through <strong class="text-white">1calendar.cda.gov.ph</strong>.</li>
                            <li>Once submitted, invite <code class="text-blue-300 bg-blue-950/60 px-1.5 py-0.5 rounded">1calendar.cda.gov.ph</code> and <code class="text-blue-300 bg-blue-950/60 px-1.5 py-0.5 rounded">videocom@cda.gov.ph</code> as Event Modifiers.</li>
                            <li>Upon approval by PPDD, the ICT team will attach the confirmed Zoom meeting credentials to your calendar schedule.</li>
                        </ol>
                    </div>
                </details>

                {{-- FAQ 5 --}}
                <details class="faq-item group bg-slate-900/60 backdrop-blur-md rounded-2xl border border-white/10 overflow-hidden transition-all duration-300 hover:border-blue-500/40 hover:bg-slate-900/80">
                    <summary class="p-5 sm:p-6 text-base sm:text-lg font-bold text-white cursor-pointer list-none flex justify-between items-center select-none marker:content-none [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">print</span>
                            How to troubleshoot printer or network connectivity issues?
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-2xl transition-transform duration-300 ease-out group-open:rotate-180 group-open:text-blue-400" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/5 space-y-2">
                        <ol class="list-decimal pl-6 space-y-2">
                            <li>Check that both the computer and printer are powered on and connected to the official CDA office network (LAN/Wi-Fi).</li>
                            <li>Confirm that the device driver is installed and designated as the default printer in your operating system.</li>
                            <li>Restart your device and printer spooler. If connection fails, submit a support ticket under <strong>Hardware/Peripherals</strong>.</li>
                        </ol>
                    </div>
                </details>
            </div>
        </div>
    </section>

</main>

{{-- Modern Footer --}}
<footer class="relative z-10 shrink-0 bg-slate-950 border-t border-white/10 text-slate-400 text-sm">
    <div class="w-full max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-white/10">
            {{-- Col 1: Branding --}}
            <div class="md:col-span-2 space-y-3">
                <div class="flex items-center gap-3 text-white font-extrabold text-xl">
                    <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Seal" class="w-9 h-9 object-contain">
                    <span>CDA-ICT Helpdesk System</span>
                </div>
                <p class="text-xs text-slate-400 max-w-md leading-relaxed">
                    Official technical assistance and cybersecurity incident reporting portal of the Cooperative Development Authority, Republic of the Philippines.
                </p>
                <div class="pt-2 text-xs text-slate-500">
                    Republic Act No. 11364 • Republic Act No. 10173 (Data Privacy Act of 2012)
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-3">Quick Navigation</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('create_ticket') }}" class="hover:text-white transition-colors">Submit Support Ticket</a></li>
                    <li><a href="{{ url('create_incident') }}" class="hover:text-white transition-colors">Report Security Incident (DBRS)</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Staff Login</a></li>
                    <li><a href="#faq-heading" class="hover:text-white transition-colors">Help & Documentation</a></li>
                </ul>
            </div>

            {{-- Col 3: Legal & Support --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-3">Official Policies</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="https://cda.gov.ph/cda-privacy-policy/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">CDA Privacy Policy</a></li>
                    <li><a href="https://cda.gov.ph" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Official CDA Portal</a></li>
                    <li><a href="https://ws.cda.gov.ph" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">CDA Workspace</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <p class="m-0">&copy; {{ $year }} Cooperative Development Authority - Information & Communications Technology Division. All rights reserved.</p>
            <p class="m-0">Powered by CDA-ICTD</p>
        </div>
    </div>
</footer>

{{-- Floating Scroll-to-Top Button --}}
<button id="scrollToTopBtn" class="scroll-top-btn hidden fixed bottom-8 right-8 z-40 bg-slate-800/80 backdrop-blur-md text-slate-50 p-3.5 rounded-full border border-white/10 shadow-xl cursor-pointer transition-all duration-300 ease-out hover:bg-blue-600 hover:-translate-y-1 hover:border-blue-500 hover:shadow-blue-500/40 flex items-center justify-center focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4" aria-label="Scroll to top" title="Back to top">
    <span class="material-symbols-outlined text-xl" aria-hidden="true">arrow_upward</span>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        const heroSection = document.querySelector('.hero');

        let isScrolling = false;
        
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    let currentScroll = window.scrollY;

                    if (currentScroll < 300) {
                        scrollToTopBtn.classList.add('hidden');
                    } else {
                        scrollToTopBtn.classList.remove('hidden');
                    }

                    if (window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
                        if (heroSection && currentScroll <= heroSection.offsetHeight) {
                            heroSection.style.backgroundPositionY = `${currentScroll * 0.4}px`;
                        }
                    }
                    isScrolling = false;
                });
                isScrolling = true;
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Single open behavior for FAQ accordions
        const details = document.querySelectorAll('details.faq-item');
        details.forEach((targetDetail) => {
            targetDetail.addEventListener('click', () => {
                details.forEach((detail) => {
                    if (detail !== targetDetail) {
                        detail.removeAttribute('open');
                    }
                });
            });
        });
    });
</script>
</body>
</html>