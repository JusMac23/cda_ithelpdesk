<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* --- Theme Variables --- */
        :root {
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --border-light: #e2e8f0;
            --border-danger: #fecaca;
        }

        body.dark {
            --card-bg: #0f172a; 
            --text-dark: #f8fafc;
            --border-light: #334155; 
            --border-danger: #7f1d1d;
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }

        /* Profile Layout Constraints */
        .profile-wrapper { max-width: 76rem; margin: 0 auto; width: 100%; }
        
        /* Header */
        .header-flex { display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; width: 100%; }
        .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.025em; transition: color 0.3s ease; }

        /* Profile Container */
        .profile-container { display: flex; flex-direction: column; gap: 2rem; }
        
        /* Modern Profile Card - Added outline matching dark mode specs */
        .profile-card { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 1.5rem; transition: background-color 0.3s ease, border-color 0.3s ease; }

        /* Specifically for the Delete Account card */
        .profile-card-danger {
            border-color: var(--border-danger);
        }
        
        /* Profile Card Content Area */
        .profile-card-content { max-width: 48rem; }

        /* Desktop & Tablet Overrides */
        @media (min-width: 640px) { 
            .profile-card { padding: 2.5rem; } 
        }
    </style>

    <div id="main-content" class="page-wrapper">
        <div class="profile-wrapper">
            
            <div class="header-flex">
                <h2 class="page-title">Profile Settings</h2>
            </div>

            <div class="profile-container">
                
                <div class="profile-card">
                    <div class="profile-card-content">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-content">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
                @can('delete_profile')
                <div class="profile-card profile-card-danger">
                    <div class="profile-card-content">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>

    <!-- Hidden form for logging out securely via POST -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- SweetAlert Notification Listener -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated Successfully',
                    text: 'For your security, you will now be logged out. Please log in again with your new password.',
                    confirmButtonColor: '#0f172a',
                    allowOutsideClick: false, // Prevents closing by clicking outside
                    allowEscapeKey: false     // Prevents closing by pressing ESC
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the hidden logout form when they click OK
                        document.getElementById('logout-form').submit();
                    }
                });
            @elseif (session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile information has been successfully updated.',
                    confirmButtonColor: '#0f172a'
                });
            @elseif (session('status'))
                Swal.fire({
                    icon: 'info',
                    title: 'Notice',
                    text: "{{ session('status') }}",
                    confirmButtonColor: '#0f172a'
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#0f172a'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#0f172a'
                });
            @endif
        });
    </script>
</x-app-layout>