@component('mail::message')

<div class="font-sans antialiased max-w-xl mx-auto my-4 text-slate-800" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; max-width: 580px; margin: 0 auto; color: #1e293b;">

    {{-- Email Header Banner --}}
    <div class="text-center pb-6 border-b border-slate-200" style="text-align: center; padding-bottom: 24px; border-bottom: 1px solid #e2e8f0;">
        <div class="inline-block p-3 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 mb-3" style="display: inline-block; padding: 12px; border-radius: 16px; background-color: #eef2ff; border: 1px solid #e0e7ff; margin-bottom: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: block; margin: 0 auto;">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight m-0" style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; letter-spacing: -0.025em;">
            Welcome to CDA-ICT Helpdesk
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 font-medium m-0" style="font-size: 13px; color: #64748b; margin: 0;">
            Official Account Creation & Access Credentials
        </p>
    </div>

    {{-- Greeting & Overview --}}
    <div class="py-6" style="padding-top: 24px; padding-bottom: 16px;">
        <p class="text-base text-slate-700 leading-relaxed m-0 mb-3" style="font-size: 15px; color: #334155; line-height: 1.6; margin: 0 0 12px 0;">
            Hello <strong style="color: #0f172a; font-weight: 700;">{{ $user->name }}</strong>,
        </p>
        <p class="text-sm text-slate-600 leading-relaxed m-0" style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
            Your account for the <strong style="color: #0f172a;">CDA-ICT Support Helpdesk System</strong> has been provisioned. You can now log in to submit, monitor, and resolve technical support requests.
        </p>
    </div>

    {{-- Modern Credentials Card --}}
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 my-4" style="border-radius: 16px; border: 1px solid #e2e8f0; background-color: #f8fafc; padding: 20px; margin: 16px 0;">
        <div class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-3" style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #4f46e5; margin-bottom: 14px;">
            Your Login Credentials
        </div>

        <table class="w-full text-left border-collapse" style="width: 100%; border-collapse: collapse;">
            <tr>
                <td class="py-2 text-xs font-semibold text-slate-500 align-top" style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #64748b; width: 35%;">
                    Email Address:
                </td>
                <td class="py-2 text-sm font-bold text-slate-900 break-all" style="padding: 8px 0; font-size: 14px; font-weight: 700; color: #0f172a; word-break: break-all;">
                    {{ $user->email }}
                </td>
            </tr>
            <tr>
                <td class="py-2 text-xs font-semibold text-slate-500 align-top" style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #64748b; border-top: 1px solid #f1f5f9;">
                    Temporary Password:
                </td>
                <td class="py-2 text-sm font-mono font-bold text-indigo-700" style="padding: 8px 0; font-size: 14px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 700; color: #4338ca; border-top: 1px solid #f1f5f9;">
                    <span style="display: inline-block; background-color: #e0e7ff; color: #3730a3; padding: 3px 10px; border-radius: 6px; font-size: 13px; letter-spacing: 0.05em;">
                        {{ $password }}
                    </span>
                </td>
            </tr>
            @if(!empty($user->region))
            <tr>
                <td class="py-2 text-xs font-semibold text-slate-500 align-top" style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #64748b; border-top: 1px solid #f1f5f9;">
                    Regional Office:
                </td>
                <td class="py-2 text-sm font-semibold text-slate-800" style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #334155; border-top: 1px solid #f1f5f9;">
                    {{ $user->region }}
                </td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Security Advisory Notice --}}
    <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 my-4" style="border-radius: 12px; border: 1px solid #fef3c7; background-color: #fffbeb; padding: 14px 16px; margin: 16px 0;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 24px; vertical-align: top; padding-right: 10px;">
                    <span style="font-size: 16px; line-height: 1;">🔒</span>
                </td>
                <td style="vertical-align: middle;">
                    <p class="text-xs text-amber-900 leading-normal m-0" style="font-size: 12px; color: #78350f; line-height: 1.5; margin: 0;">
                        <strong style="color: #92400e; font-weight: 700;">Security Recommendation:</strong> For your protection, please log in and change this temporary password immediately under your <strong>Profile Settings</strong>.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Call to Action Button --}}
    <div class="text-center py-5" style="text-align: center; padding: 20px 0;">
        <a href="{{ route('login') }}" 
           class="inline-block px-7 py-3.5 rounded-xl font-bold text-sm text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/30 transition-all"
           style="display: inline-block; padding: 12px 28px; border-radius: 10px; font-size: 14px; font-weight: 700; color: #ffffff; background-color: #4f46e5; text-decoration: none; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);"
           target="_blank">
            Log In to CDA-ICT Helpdesk &rarr;
        </a>
    </div>

    {{-- Secondary Link Note --}}
    <p class="text-xs text-slate-400 text-center m-0 mb-6" style="font-size: 12px; color: #94a3b8; text-align: center; margin: 0 0 24px 0;">
        Or paste this URL into your browser: <br>
        <a href="{{ route('login') }}" style="color: #6366f1; text-decoration: underline; word-break: break-all;">{{ route('login') }}</a>
    </p>

    {{-- Footer --}}
    <div class="pt-6 border-t border-slate-200 text-center" style="padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center;">
        <p class="text-xs text-slate-500 m-0 mb-1" style="font-size: 12px; color: #64748b; margin: 0 0 4px 0;">
            This is an automated notification from the <strong>CDA-ICT Support Helpdesk System</strong>.
        </p>
        <p class="text-xs text-slate-400 m-0" style="font-size: 11px; color: #94a3b8; margin: 0;">
            Please do not reply directly to this email. For inquiries, please contact your ICT support administrator.
        </p>
    </div>

</div>

@endcomponent