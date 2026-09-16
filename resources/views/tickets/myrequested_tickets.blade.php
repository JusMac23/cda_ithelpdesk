<x-app-layout>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

    <style>
        /* --- Theme Variables --- */
        :root {
            --card-bg: #ffffff;
            --bg-alt: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --border-subtle: #f1f5f9;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #334155;

            /* Action Buttons (Gray) */
            --btn-gray-bg: #f1f5f9;
            --btn-gray-text: #475569;
            --btn-gray-border: #e2e8f0;
            --btn-gray-hover-bg: #e2e8f0;
            --btn-gray-hover-text: #0f172a;
            --primary-indigo: #4f46e5; 
            --indigo-hover: #4338ca; 
            
            /* Enhanced Status Badges - Light */
            --badge-new-bg: #f3e8ff; --badge-new-text: #6b21a8;     /* New/Open */
            --badge-res-bg: #dcfce7; --badge-res-text: #166534;     /* Resolved */
            --badge-pen-bg: #fef9c3; --badge-pen-text: #854d0e;     /* Pending */
            --badge-rea-bg: #e0f2fe; --badge-rea-text: #0369a1;     /* Reassigned */
            --badge-clo-bg: #f1f5f9; --badge-clo-text: #475569;     /* Closed/Default */

            /* Enhanced Priority Badges - Light */
            --prio-low-bg: #ecfdf5; --prio-low-border: #34d399; --prio-low-text: #059669;
            --prio-med-bg: #fffbeb; --prio-med-border: #fbbf24; --prio-med-text: #d97706;
            --prio-high-bg: #fff1f2; --prio-high-border: #f43f5e; --prio-high-text: #e11d48;
            --prio-crit-bg: #ef4444; --prio-crit-border: #b91c1c; --prio-crit-text: #ffffff;
            --prio-def-bg: #f8fafc; --prio-def-border: #94a3b8; --prio-def-text: #64748b;
        }

        body.dark {
            --card-bg: #0f172a; 
            --bg-alt: #1e293b; 
            --text-dark: #f8fafc;
            --text-muted: #9ca3af;
            --border-light: #334155; 
            --border-subtle: #1e293b;
            --input-bg: #0f172a;
            --input-border: #4b5563;
            --input-text: #f1f5f9;

            /* Action Buttons (Gray) - Dark */
            --btn-gray-bg: #1e293b;
            --btn-gray-text: #9ca3af;
            --btn-gray-border: #334155;
            --btn-gray-hover-bg: #334155;
            --btn-gray-hover-text: #f8fafc;

            /* Enhanced Status Badges - Dark */
            --badge-new-bg: rgba(147, 51, 234, 0.2); --badge-new-text: #d8b4fe;
            --badge-res-bg: rgba(22, 101, 52, 0.4);  --badge-res-text: #4ade80;
            --badge-pen-bg: rgba(133, 77, 14, 0.4);  --badge-pen-text: #facc15;
            --badge-rea-bg: rgba(3, 105, 161, 0.4);  --badge-rea-text: #38bdf8;
            --badge-clo-bg: rgba(71, 85, 105, 0.4);  --badge-clo-text: #94a3b8;

            /* Enhanced Priority Badges - Dark */
            --prio-low-bg: rgba(5, 150, 105, 0.15); --prio-low-border: #059669; --prio-low-text: #34d399;
            --prio-med-bg: rgba(217, 119, 6, 0.15); --prio-med-border: #d97706; --prio-med-text: #fbbf24;
            --prio-high-bg: rgba(225, 29, 72, 0.15);--prio-high-border: #e11d48; --prio-high-text: #fb7185;
            --prio-crit-bg: #dc2626; --prio-crit-border: #991b1b; --prio-crit-text: #ffffff;
            --prio-def-bg: rgba(71, 85, 105, 0.15); --prio-def-border: #475569; --prio-def-text: #94a3b8;
        }

        /* --- Global Box Sizing & Font Fix --- */
        *, *::before, *::after { box-sizing: border-box; }
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        /* --- Material Symbols Global Engine --- */
        .material-symbols-outlined { font-family: 'Material Symbols Outlined'; font-weight: normal; font-style: normal; font-size: 24px; line-height: 1; display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr; -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility; -moz-osx-font-smoothing: grayscale; font-feature-settings: 'liga'; user-select: none; vertical-align: middle; }

        /* --- Main Container --- */
        .content-panel { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 1.25rem; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease; }
        
        .header-flex { display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; width: 100%; }
        .title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.025em; transition: color 0.3s ease; }

        /* --- Action Container (Toolbar Layout) --- */
        .action-container { display: flex; flex-direction: column; width: 100%; gap: 1rem; margin-bottom: 1.5rem; }
        .action-left-group { display: flex; flex-direction: column; width: 100%; gap: 0.75rem; }
        .action-form { margin: 0; width: 100%; }

        /* Auto Reload Toggle */
        .auto-reload-label { display: flex; align-items: center; font-size: 0.9rem; font-weight: 600; color: var(--text-muted); cursor: pointer; width: 100%; justify-content: flex-start; padding: 0.5rem 0; white-space: nowrap; transition: color 0.3s ease; }
        .auto-reload-checkbox { margin-right: 0.5rem; cursor: pointer; width: 1.15rem; height: 1.15rem; accent-color: #4f46e5; border-radius: 0.25rem; }

        /* --- Buttons - Uniform 44px Heights --- */
        .btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.5rem; border-radius: 0.5rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s ease; width: 100%; text-decoration: none; font-family: inherit; white-space: nowrap; box-sizing: border-box; }
        
        /* Button Icon Adjustments for Material Symbols */
        .btn i,
        .btn .material-symbols-outlined { margin-right: 0.5rem; font-size: 20px; line-height: 1; flex-shrink: 0; }
        
        .btn .btn-count { margin-left: 0.5rem; background-color: rgba(255, 255, 255, 0.25); padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; }

        .btn-green { background-color: #10b981; color: white; box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }
        .btn-green:hover { background-color: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); color: white;}
        .btn-green:active { transform: translateY(0); box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }
        
        .btn-blue { background-color: #3b82f6; color: white; box-shadow: 0 1px 2px rgba(59, 130, 246, 0.2); }
        .btn-blue:hover { background-color: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: white;}
        
        .btn-red { background-color: #ef4444; color: white; box-shadow: 0 1px 2px rgba(239, 68, 68, 0.2); }
        .btn-red:hover { background-color: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); color: white;}
        
        .btn-yellow { background-color: #eab308; color: white; box-shadow: 0 1px 2px rgba(234, 179, 8, 0.2); }
        .btn-yellow:hover { background-color: #ca8a04; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3); color: white;}

        .btn-indigo { background-color: #4f46e5; color: white; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-indigo:hover { background-color: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); color: white;}

        .btn-gray { background-color: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-border); }
        .btn-gray:hover { background-color: var(--btn-gray-hover-bg); color: var(--btn-gray-hover-text); }

        .form-footer { display: flex; flex-direction: column; padding-top: 1.5rem; border-top: 1px solid var(--border-light); margin-top: 1.5rem; gap: 0.75rem; transition: border-color 0.3s ease; }
        @media (min-width: 768px) { .form-footer { flex-direction: row; justify-content: flex-end; align-items: center; } }
        
        /* --- Search Form Group --- */
        .search-form { display: flex; align-items: stretch; width: 100%; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border-radius: 0.5rem; }
        .search-input { height: 44px; flex: 1; min-width: 0; padding: 0 1rem; font-size: 0.95rem; font-family: inherit; color: var(--input-text); border: 1px solid var(--input-border); border-right: none; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; outline: none; transition: all 0.2s; position: relative; z-index: 1; background-color: var(--input-bg); }
        .search-input:focus { border-color: #6366f1; box-shadow: inset 0 0 0 1px #6366f1, 0 0 0 3px rgba(99, 102, 241, 0.15); z-index: 10; }
        .search-btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.25rem; border: none; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background-color: #4f46e5; color: white; cursor: pointer; transition: background-color 0.2s; z-index: 2; width: auto; }
        .search-btn:hover { background-color: #4338ca; }
        .search-btn i, .search-btn .material-symbols-outlined { font-size: 20px; }

        /* --- Data Table --- */
        .table-container { width: 100%; overflow-x: auto; background-color: var(--card-bg); border-radius: 0.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-top: 1.5rem; -webkit-overflow-scrolling: touch; display: block; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .data-table { width: 100%; min-width: 1200px; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        .data-table th { padding: 1rem 1.25rem; background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--border-light); white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .data-table td { padding: 1.25rem; border-bottom: 1px solid var(--border-subtle); color: var(--text-dark); vertical-align: middle; font-weight: 500; transition: color 0.3s ease, border-color 0.3s ease; }
        .data-table tbody tr { transition: background-color 0.15s; }
        .data-table tbody tr:hover { background-color: var(--bg-alt); }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }
        .text-truncate { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
        
        /* Thumbnails */
        .thumb-img { width: 3rem; height: 3rem; object-fit: cover; border-radius: 0.5rem; border: 1px solid var(--border-light); transition: all 0.2s; cursor: pointer; }
        .thumb-img:hover { opacity: 0.8; border-color: var(--text-muted); }

        /* Terms & Submit Button */
        .terms-wrapper { margin-top: 1.25rem; margin-bottom: 1.25rem; width: 100%; }
        .terms-label { display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; }
        .terms-checkbox { margin-top: 0.2rem; width: 1.1rem; height: 1.1rem; accent-color: var(--primary-indigo); cursor: pointer; flex-shrink: 0; }
        .terms-link { color: var(--primary-indigo); font-weight: 600; }
        .terms-link:hover { text-decoration: underline; }

        .btn-submit { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 2rem; background-color: var(--primary-indigo); color: #f8fafc; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-submit:hover:not(:disabled) { background-color: var(--indigo-hover); color: #f8fafc; transform: translateY(-1px); }
        .btn-submit:disabled { background-color: #cbd5e1; color: #f8fafc; cursor: not-allowed; box-shadow: none; transform: none; }

        /* --- Enhanced Status Badges --- */
        .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease; }
        /* Status Indicator Dot */
        .badge::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; flex-shrink: 0; }
        .status-new { background-color: var(--badge-new-bg); color: var(--badge-new-text); }
        .status-resolved { background-color: var(--badge-res-bg); color: var(--badge-res-text); }
        .status-pending { background-color: var(--badge-pen-bg); color: var(--badge-pen-text); }
        .status-reassigned { background-color: var(--badge-rea-bg); color: var(--badge-rea-text); }
        .status-closed { background-color: var(--badge-clo-bg); color: var(--badge-clo-text); }
        
        /* Modify dot behavior based on specific status context */
        .status-closed::before { display: none; } /* Closed tickets don't need active indicators */
        .status-resolved::before { opacity: 0.5; } /* Subdued dot for resolved */
        .status-pending::before { animation: blink 1.5s infinite; } /* Pending requires attention */

        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* --- Enhanced Priority Badges --- */
        .badge-priority { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.2; border-style: solid; border-width: 1px; border-radius: 9999px; transition: all 0.2s ease; }
        .priority-low { background-color: var(--prio-low-bg); border-color: var(--prio-low-border); color: var(--prio-low-text); }
        .priority-medium { background-color: var(--prio-med-bg); border-color: var(--prio-med-border); color: var(--prio-med-text); }
        .priority-high { background-color: var(--prio-high-bg); border-color: var(--prio-high-border); color: var(--prio-high-text); }
        .priority-default { background-color: var(--prio-def-bg); border-color: var(--prio-def-border); color: var(--prio-def-text); }
        
        /* Critical Priority gets solid fill + emergency pulse animation */
        .priority-critical { background-color: var(--prio-crit-bg); border-color: var(--prio-crit-border); color: var(--prio-crit-text); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); animation: pulse-critical 2s infinite; }

        @keyframes pulse-critical { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        /* --- Action Links inside Table --- */
        .action-group { display: flex; flex-direction: column; gap: 0.5rem; min-width: 140px; }
        .action-link { display: inline-flex; align-items: center; justify-content: flex-start; height: 34px; padding: 0 0.85rem; border-radius: 0.375rem; font-size: 0.85rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s; text-decoration: none; background: transparent; white-space: nowrap; box-sizing: border-box; }
        
        /* Material Symbol inside Action Link */
        .action-link i,
        .action-link .material-symbols-outlined { margin-right: 0.4rem; width: 18px; height: 18px; font-size: 18px; line-height: 1; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* Green */
        .link-green { color: #16a34a; border: 1px solid #bbf7d0; } 
        .link-green:hover { background-color: #f0fdf4; color: #15803d; border-color: #86efac; }
        
        .link-blue { color: #3b82f6; border: 1px solid #bfdbfe; } 
        .link-blue:hover { background-color: #eff6ff; color: #1d4ed8; border-color: #93c5fd; }
        
        .link-yellow { color: #d97706; border: 1px solid #fde68a; } 
        .link-yellow:hover { background-color: #fffbeb; color: #b45309; border-color: #fcd34d; }
        
        .link-indigo { color: #4f46e5; border: 1px solid #a5b4fc; } 
        .link-indigo:hover { background-color: #e0e7ff; color: #3730a3; border-color: #818cf8; }
        
        .link-red { color: #ef4444; border: 1px solid #fecaca; } 
        .link-red:hover { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

        /* Dark Mode Action Link Overrides */
        body.dark .link-blue { color: #60a5fa; border-color: #1e3a8a; }
        body.dark .link-blue:hover { background-color: rgba(30, 58, 138, 0.4); color: #93c5fd; }
        body.dark .link-yellow { color: #fbbf24; border-color: #78350f; }
        body.dark .link-yellow:hover { background-color: rgba(120, 53, 15, 0.4); color: #fcd34d; }
        body.dark .link-indigo { color: #818cf8; border-color: #3730a3; }
        body.dark .link-indigo:hover { background-color: rgba(49, 46, 129, 0.4); color: #a5b4fc; }
        body.dark .link-red { color: #f87171; border-color: #7f1d1d; }
        body.dark .link-red:hover { background-color: rgba(127, 29, 29, 0.4); color: #fca5a5; }

        /* --- Modern UI Pagination --- */
        .pagination-wrapper { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); width: 100%; transition: border-color 0.3s ease; }
        .pagination-wrapper nav { display: flex; flex-direction: column; gap: 1.25rem; width: 100%; align-items: center; }
        .pagination-wrapper p { margin: 0; font-size: 0.875rem; color: var(--text-muted); font-weight: 500; text-align: center; transition: color 0.3s ease; }
        .pagination-wrapper p span { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }
        .pagination-wrapper div > span.relative.z-0.inline-flex,
        .pagination-wrapper .flex.justify-between { display: flex; flex-wrap: wrap; gap: 0.5rem; box-shadow: none !important; justify-content: center; align-items: center; }
        .pagination-wrapper a, 
        .pagination-wrapper span[aria-current="page"] > span,
        .pagination-wrapper span[aria-disabled="true"] > span { display: inline-flex; align-items: center; justify-content: center; min-width: 2.25rem; height: 2.25rem; padding: 0 0.5rem; border-radius: 0.375rem !important; font-size: 0.875rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.2s ease; border: 1px solid transparent; margin: 0 !important; text-decoration: none; line-height: 1; }
        .pagination-wrapper a { background-color: var(--card-bg); color: var(--text-muted); border-color: var(--border-light); }
        .pagination-wrapper a:hover { background-color: var(--bg-alt); color: var(--text-dark); border-color: var(--input-border); transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .pagination-wrapper span[aria-current="page"] > span { background-color: #4f46e5; color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25); z-index: 2; position: relative; }
        .pagination-wrapper span[aria-disabled="true"] > span { background-color: var(--bg-alt); color: var(--text-muted); border-color: var(--border-light); cursor: not-allowed; opacity: 0.7; }
        .pagination-wrapper span[aria-disabled="true"]:not([aria-label]) > span { background: transparent; border: none; opacity: 1; color: var(--text-muted); }
        .pagination-wrapper svg { width: 1.25rem !important; height: 1.25rem !important; display: block; }

        /* --- Modal Enhancements --- */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 1; visibility: visible; transition: all 0.3s ease; }
        .modal-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .modal-box { position: relative; background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); width: 100%; max-width: 60rem; max-height: 90vh; overflow-y: auto; padding: 1.5rem; transform: scale(1); transition: transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease; }
        .modal-overlay.hidden .modal-box { transform: scale(0.95); }
        
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text-muted); font-size: 2rem; background: none; border: none; cursor: pointer; transition: all 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: var(--text-dark); }
        
        .modal-title { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; padding-right: 2.5rem; letter-spacing: -0.025em; transition: color 0.3s ease, border-color 0.3s ease; }
        
        /* Modal Form Grids */
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; width: 100%; }
        .form-group { display: flex; flex-direction: column; width: 100%; }
        .form-label { font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted); transition: color 0.3s ease; }
        .form-input, .form-select { height: 44px; padding: 0 1rem; border: 1px solid var(--input-border); border-radius: 0.5rem; font-size: 0.95rem; color: var(--input-text); width: 100%; box-sizing: border-box; outline: none; transition: all 0.2s; background-color: var(--input-bg); font-family: inherit; }
        .form-input:focus, .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        textarea.form-input { height: auto; resize: vertical; padding: 0.75rem 1rem; min-height: 100px; }

        fieldset.form-fieldset { border: 1px solid var(--border-light); border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.75rem; background: var(--card-bg); transition: background-color 0.3s ease, border-color 0.3s ease; }
        fieldset.form-fieldset legend { font-weight: 700; color: var(--text-dark); padding: 0 0.5rem; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.05em; transition: color 0.3s ease; }
        
        .modal-footer { display: flex; flex-direction: column; padding-top: 1.5rem; border-top: 1px solid var(--border-light); margin-top: 1.5rem; gap: 0.75rem; transition: border-color 0.3s ease; }

        /* Readonly & Disabled Input Styling */
        .form-input:read-only, .form-select:disabled, .form-input[readonly] { background-color: var(--bg-alt) !important; color: var(--text-muted) !important; cursor: not-allowed; opacity: 0.85; border-color: var(--border-light); }
        .form-input[readonly]:focus { box-shadow: none; border-color: var(--border-light); }

        /* File Input Styling */
        input[type="file"].form-input { padding: 0.4rem 0.5rem; line-height: 1.75; }
        input[type="file"]::file-selector-button { margin-right: 1rem; border: none; background: var(--btn-gray-bg); color: var(--btn-gray-text); padding: 0.4rem 0.8rem; border-radius: 0.25rem; cursor: pointer; transition: all 0.2s ease; font-weight: 600; font-size: 0.85rem; font-family: inherit; }
        input[type="file"]::file-selector-button:hover { background: var(--btn-gray-hover-bg); color: var(--btn-gray-hover-text); }

        /* Full-Screen Loading Overlay */
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); display: none; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; color: #ffffff; }
        .loading-overlay.active { display: flex; }
        .spinner { width: 44px; height: 44px; border: 4px solid rgba(255, 255, 255, 0.3); border-top-color: #ffffff; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* --- Responsive Overrides --- */
        @media (max-width: 639px) {
            .pagination-wrapper nav .hidden { display: none !important; }
            .pagination-wrapper nav .sm\:hidden { display: flex; width: 100%; justify-content: space-between; }
        }

        @media (min-width: 768px) {
            .content-panel { padding: 2rem; }
            .header-flex { flex-direction: row; justify-content: space-between; align-items: center; }
            .action-container { flex-direction: row; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
            .action-left-group { flex-direction: row; width: auto; flex: 1; align-items: center; }
            .action-form { width: auto; }
            .search-form { max-width: 350px; min-width: 250px; width: auto; }
            .auto-reload-label { width: auto; padding: 0; justify-content: flex-start; margin-left: 0.5rem; }
            .btn { width: auto; }

            /* Modal Layout Enhancements */
            .modal-box { padding: 2.5rem; }
            .form-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
            .col-span-2 { grid-column: span 2; }
            .modal-footer { flex-direction: row; justify-content: flex-end; }
            .form-group { width: 100%; }

            .pagination-wrapper nav { flex-direction: row; justify-content: space-between; }
            .pagination-wrapper nav > div.sm\:hidden { display: none !important; }
            .pagination-wrapper nav > div.hidden.sm\:flex-1 { display: flex !important; width: 100%; justify-content: space-between; align-items: center; }
        }
    </style>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <p style="font-size: 1rem; font-weight: 600; letter-spacing: 0.025em;">Processing Ticket, please wait...</p>
    </div>

    <div id="main-content" class="page-wrapper">
        <div id="ticketsContent">
            <div class="content-panel">

                <div class="header-flex">
                    <h3 class="title">My Requested Tickets</h3>
                </div>

                <div class="action-container">

                    @can('create_myrequested_tickets')
                    <button id="openAddTicketModalBtn" class="btn btn-green">
                        <span class="material-symbols-outlined">add</span> Add Ticket
                    </button>
                    @endcan

                    <div class="action-left-group">
                        <label class="auto-reload-label">
                            <input type="checkbox" id="autoReloadCheckbox" class="auto-reload-checkbox">
                            <span>(<span id="countdown">60</span>s) Auto-Reload</span>
                        </label>
                    </div>

                    @can('search_myrequested_tickets')
                    <form action="{{ route('myrequested_tickets.index') }}" method="GET" class="search-form">
                        <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search tickets..." class="search-input" autocomplete="off">
                        <button type="submit" class="search-btn" aria-label="Search">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                    @endcan
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="text-center">Tracking ID</th>
                                <th>Requested By</th>
                                <th>Division</th>
                                <th>Technical Service</th>
                                <th>Request Details</th>
                                <th>Assigned Personnel</th>
                                <th>Date Created</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td class="text-center font-bold" style="font-size: 0.95rem;">{{ $ticket->ticket_number }}</td>
                                    <td>{{ $ticket->firstname }} {{ $ticket->middle_initial }} {{ $ticket->lastname }}</td>
                                    <td>{{ $ticket->division }}</td>
                                    <td>{{ $ticket->service }}</td>
                                    <td>
                                        <span class="text-truncate" title="{{ $ticket->request }}">
                                            {{ $ticket->request }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->it_personnel }}</td>
                                    <td style="color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}
                                    </td>
                                   <td class="text-center">
                                        @php
                                            $priority = trim($ticket->priority);
                                            $badgeClass = match($priority) {
                                                'Low'       => 'priority-low',
                                                'Medium'    => 'priority-medium',
                                                'High'      => 'priority-high',
                                                'Critical'  => 'priority-critical',
                                                default     => 'priority-default',
                                            };
                                        @endphp
                                        <span class="badge-priority {{ $badgeClass }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $status = trim($ticket->status);
                                            $badgeClass = match($status) {
                                                'Resolved'              => 'status-resolved',
                                                'Pending'               => 'status-pending',
                                                'Pending/Re-Assigned'   => 'status-reassigned',
                                                default                 => 'status-default',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="action-group">
                                            
                                           @can('view_ticket_details_myrequested_tickets')
                                                <a href="{{ route('tickets.myrequested.view', $ticket->ticket_id) }}" class="action-link link-green">
                                                    <span class="material-symbols-outlined">visibility</span> View Details
                                                </a>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center" style="padding: 3rem; color: var(--text-muted); font-size: 1rem;">
                                        No Requested Ticket found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>

        {{-- Add Ticket Modal --}}
        <div id="addticketModal" class="modal-overlay hidden">
            <div class="modal-box">
                
                <button id="closeModal" class="close-btn" aria-label="Close">&times;</button>
                
                @if ($errors->any())
                    <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid #fca5a5; color: #b91c1c; padding: 1.25rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                        <h4 style="margin:0 0 0.5rem 0; font-weight: 700; color: #ef4444;"><span class="material-symbols-outlined">exclamation</span> Please fix the following errors:</h4>
                        <ul style="margin:0; padding-left: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <h2 class="modal-title">Create New Ticket</h2>

                <form id="createTicketForm" action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Client Information -->
                    <fieldset class="form-fieldset">
                        <legend>Client Information</legend>

                        @php
                            $parts = explode(' ', trim(auth()->user()->name));
                            $lastName = count($parts) > 1 ? array_pop($parts) : '';
                            $middleInitial = '';

                            // Check if the new last element is a middle initial (e.g., "A." or "A")
                            if (count($parts) > 0) {
                                $lastPart = end($parts);
                                if (preg_match('/^[A-Za-z]\.?$/', $lastPart)) {
                                    $middleInitial = array_pop($parts);
                                }
                            }

                            $firstName = implode(' ', $parts);
                        @endphp

                        <div class="form-grid" style="display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 1rem;">
                            <div class="form-group">
                                <label for="firstname" class="form-label">First Name <span class="text-required">*</span></label>
                                <input type="text" id="firstname" name="firstname" value="{{ $firstName }}" readonly class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="middle_initial" class="form-label">Middle Initial<span class="text-required">*</span></label>
                                <input type="text" id="middle_initial" name="middle_initial" value="{{ $middleInitial }}" readonly class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="form-label">Last Name <span class="text-required">*</span></label>
                                <input type="text" id="lastname" name="lastname" value="{{ $lastName }}" readonly class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="text-required">*</span></label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" readonly class="form-input">
                        </div>

                        <div class="form-grid grid-cols-2">
                            <div class="form-group">
                                <label class="form-label">Date Created</label>
                                <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                                <input type="hidden" name="date_created" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                            </div>

                            <div class="form-group">
                                <label for="division" class="form-label">Section / Division</label>
                                <select class="form-select" id="division" name="division" required>
                                    <option value="" selected disabled>Select Division</option>
                                    @forelse ($sections_divisions ?? [] as $division)
                                        <option value="{{ $division }}">{{ $division }}</option>
                                    @empty
                                        <option value="" disabled>No divisions available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-grid grid-cols-2">
                            <div class="form-group">
                                <label for="device" class="form-label">Device <span class="text-required">*</span></label>
                                <select id="device" name="device" required class="form-select">
                                    <option value="" disabled selected>Select Device</option>
                                    @foreach (['Desktop PC', 'Laptop/Netbook PC', 'Tablet PC', 'All-in-1 Printer', 'Printer Only', 'Scanner Only', 'Others'] as $device)
                                        <option value="{{ $device }}">{{ $device }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="service" class="form-label">Technical Service</label>
                                <select class="form-select" id="service" name="service" required>
                                    <option value="" selected disabled>Select Technical Service</option>
                                    @forelse ($technical_services ?? [] as $service)
                                        <option value="{{ $service }}">{{ $service }}</option>
                                    @empty
                                        <option value="" disabled>No technical services available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="request" class="form-label">Request Details <span class="text-required">*</span></label>
                            <textarea id="request" name="request" rows="4" placeholder="Describe the issue or request in detail..." required class="form-input"></textarea>
                        </div>

                        <div class="form-grid grid-cols-2">
                            <div class="form-group">
                                <label for="photo" class="form-label">Attach Photo (Optional)</label>
                                <input type="file" id="photo" name="photo" accept="image/*" class="form-input">
                            </div>

                            <div class="form-group">
                                <label for="priority" class="form-label">Priority <span class="text-required">*</span></label>
                                <select id="priority" name="priority" required class="form-select">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            </div>
                        </div> 
                    </fieldset>

                    <!-- Designated Personnel -->
                    <fieldset class="form-fieldset">
                        <legend>Designated Personnel</legend>
                        
                        <div class="form-grid grid-cols-2">
                            <div class="form-group">
                                <label for="it_area" class="form-label">Region <span class="text-required">*</span></label>
                                <select id="it_area" name="it_area" required class="form-select">
                                    <option value="" disabled selected>Select Region</option>
                                    @forelse ($it_area ?? [] as $area)
                                        <option value="{{ $area }}">{{ $area }}</option>
                                    @empty
                                        <option value="" disabled>No regions available</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" id="status" name="status" value="Pending" readonly class="form-input">
                            </div>
                        </div>

                        <input type="hidden" id="it_personnel" name="it_personnel" value="">
                        <input type="hidden" id="it_email" name="it_email" value="">
                    </fieldset>

                    <!-- Form Footer & Terms -->
                    <div class="terms-wrapper" style="margin-top: 1rem;">
                        <label class="terms-label" for="terms_agree">
                            <input type="checkbox" id="terms_agree" name="terms_agree" required class="terms-checkbox">
                            <span>I have read and agree to the <a href="https://cda.gov.ph/cda-privacy-policy/" class="terms-link" target="_blank">Terms and Conditions</a> and the <a href="https://cda.gov.ph/cda-privacy-policy/" class="terms-link" target="_blank">Privacy Policy</a>, and I confirm that the information provided is accurate and true to the best of my knowledge. <span style="color:#ef4444;">*</span></span>
                        </label>
                    </div>

                    <div class="form-footer">
                        <button type="submit" id="submitTicketBtn" class="btn-submit" disabled>Submit Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // --- LOADING OVERLAY HELPER UTILITIES ---
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.add('active');
            }

            function hideLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.remove('active');
            }

            // Helper to get CSS variable colors for SweetAlert Dark Mode
            const getComputedColor = (cssVar) => getComputedStyle(document.body).getPropertyValue(cssVar).trim();

            // --- SWEETALERT NOTIFICATIONS ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
                    timer: 2500,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{!! addslashes(session("error")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            // --- AUTO-RELOAD & COUNTDOWN ---
            const checkbox = document.getElementById('autoReloadCheckbox');
            const countdownDisplay = document.getElementById('countdown');
            let intervalId = null;
            let countdown = 60;

            if (checkbox) {
                const isChecked = localStorage.getItem('autoReload') === 'true';
                checkbox.checked = isChecked;

                if (isChecked) startAutoReload();

                checkbox.addEventListener('change', function () {
                    localStorage.setItem('autoReload', checkbox.checked);
                    if (checkbox.checked) {
                        startAutoReload();
                    } else {
                        stopAutoReload();
                    }
                });
            }

            function startAutoReload() {
                countdown = 60;
                updateCountdown();
                intervalId = setInterval(() => {
                    countdown--;
                    updateCountdown();
                    if (countdown <= 0) location.reload();
                }, 1000);
            }

            function stopAutoReload() {
                clearInterval(intervalId);
                countdown = 60;
                updateCountdown();
            }

            function updateCountdown() {
                if (countdownDisplay) countdownDisplay.textContent = countdown;
            }

            // --- SHARED MODAL FUNCTIONS ---
            const body = document.body;

            function openModal(modal) {
                if (!modal) return;
                modal.classList.remove('hidden');
                body.classList.add('overflow-hidden');
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            }

            const itMapping = @json($it_mapping ?? []);

            // --- ADD TICKET MODAL LOGIC ---
            const addModal = document.getElementById('addticketModal');
            const assignModal = document.getElementById('assignModal');
            const editModal = document.getElementById('editModal');

            if (addModal) {
                const closeAddBtn = addModal.querySelector('#closeModal');
                const openAddBtns = document.querySelectorAll('#openAddTicketModalBtn, #openTicketModal, .open-ticket-modal, [data-modal-target="addticketModal"]');

                openAddBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(addModal);
                    });
                });

                if (closeAddBtn) {
                    closeAddBtn.addEventListener('click', () => closeModal(addModal));
                }

                addModal.addEventListener('click', function(e) {
                    if (e.target === addModal) closeModal(addModal);
                });

                @if ($errors->any())
                    openModal(addModal);
                @endif

                // Get NextAssignment Map safely
                const nextAssignmentMap = @json($nextAssignment ?? new \stdClass());
                
                // Scope elements to addModal to avoid selector collisions
                const serviceSelect = addModal.querySelector('select[name="service"]');
                const regionSelect = addModal.querySelector('select[name="it_area"]');
                const personnelInput = addModal.querySelector('input[name="it_personnel"]');
                const emailInput = addModal.querySelector('input[name="it_email"]');

                function updatePersonnelAndEmails() {
                    if (!regionSelect || !serviceSelect || !personnelInput || !emailInput) return;
                    
                    const selectedRegion = regionSelect.value;
                    const selectedService = serviceSelect.value;

                    personnelInput.value = '';
                    emailInput.value = '';

                    if (!selectedRegion) return;

                    const exactKey = `${selectedRegion}_${selectedService}`;
                    const defaultKey = `${selectedRegion}_default`;
                    const assignedPerson = nextAssignmentMap[exactKey] || nextAssignmentMap[defaultKey];

                    if (assignedPerson) {
                        personnelInput.value = assignedPerson.name;
                        emailInput.value = assignedPerson.email;
                    } else {
                        personnelInput.value = 'No personnel found for this region';
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No Personnel Found',
                                text: 'There is no IT personnel assigned to this region/service yet.',
                                timer: 2500,
                                showConfirmButton: false,
                                background: getComputedColor('--card-bg'),
                                color: getComputedColor('--text-dark')
                            });
                        }
                    }
                }

                if (serviceSelect) serviceSelect.addEventListener('change', updatePersonnelAndEmails);
                if (regionSelect) regionSelect.addEventListener('change', updatePersonnelAndEmails);

                // --- FORM VALIDATION & SUBMIT LOADING ---
                const ticketForm = addModal.querySelector('#createTicketForm');
                if (ticketForm) {
                    ticketForm.addEventListener('submit', function(e) {
                        let isValid = true;
                        const requiredFields = this.querySelectorAll('[required]');
                        
                        requiredFields.forEach(field => {
                            if (!field.value.trim()) {
                                isValid = false;
                                field.classList.add('border-red-500', 'bg-red-50');
                            } else {
                                field.classList.remove('border-red-500', 'bg-red-50');
                            }
                        });

                        if (!isValid) {
                            e.preventDefault();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Missing Information',
                                    text: 'Please fill in all required fields marked with *.',
                                    confirmButtonColor: '#4f46e5',
                                    background: getComputedColor('--card-bg'),
                                    color: getComputedColor('--text-dark')
                                });
                            } else {
                                alert('Please fill in all required fields marked with *.');
                            }
                        } else {
                            // Show loading overlay on valid form submission
                            showLoading();
                        }
                    });
                }

                // Enable/Disable Submit button on Terms acceptance
                const termsCheckbox = addModal.querySelector('#terms_agree');
                const submitBtn = addModal.querySelector('#submitTicketBtn');

                if (termsCheckbox && submitBtn) {
                    submitBtn.disabled = !termsCheckbox.checked;

                    termsCheckbox.addEventListener('change', function() {
                        submitBtn.disabled = !this.checked;
                    });
                }
            }
            
            // --- KEYBOARD ACCESSIBILITY (ESC KEY) ---
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    if (addModal) closeModal(addModal);
                    if (assignModal) closeModal(assignModal);
                    if (editModal) closeModal(editModal);
                }
            });
        });
    </script>
</x-app-layout>