<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ITHelpdesk</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles & Scripts (Vite handles Tailwind + JS build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main content area -->
        <div id="main-content" class="flex-1 flex flex-col transition-all duration-300 ease-in-out">
            <!-- Top bar -->
            <div id="page-header" class="flex justify-end items-center h-16 px-6 bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700 transition-all duration-300 ease-in-out">
                <div class="flex items-center space-x-4">
                    @php
                        $nowPH = \Carbon\Carbon::now('Asia/Manila')->format('F j, Y - h:i A');
                        $user = Auth::user();
                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=fff&size=64';
                    @endphp

                    <!-- Date Display -->
                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                        <span class="material-icons-outlined text-xl">today</span>
                        <span class="text-sm ml-1">{{ $nowPH }}</span>
                    </div>

                    <!-- Notifications Dropdown -->
                    <x-dropdown align="right">
                        <x-slot name="trigger">
                            <div class="relative" x-data="notificationHandler()" x-init="init()">
                                <button @click="toggleNotifications()" 
                                        class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 relative p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">

                                    <span class="material-icons-outlined text-2xl">notifications</span>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-600 text-white rounded-full flex items-center justify-center shadow">
                                            <span class="text-xs leading-none" x-text="unreadCount"></span>
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 min-w-[16rem] max-w-[28rem] w-auto">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">Notifications</h3>
                                    <template x-if="unreadCount > 0">
                                        <button @click="deleteAll()" class="text-xs text-red-600 dark:text-red-400 hover:underline focus:outline-none">
                                            Mark all as read
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto min-w-[16rem] max-w-[28rem] w-auto" x-data="notificationHandler()" x-init="init()">
                                <template x-if="unreadCount === 0 && notifications.length === 0">
                                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                        <span class="material-icons-outlined text-3xl opacity-50 mb-2">notifications_off</span>
                                        <p class="text-sm">No new notifications</p>
                                    </div>
                                </template>
                                
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <div class="flex justify-between items-start">
                                            <!-- 🔹 Black for readability -->
                                            <p class="text-sm text-black break-words flex-1 mr-2" x-text="notification.message"></p>
                                            <button @click="deleteNotification(notification.id)" 
                                                    class="text-xs text-red-600 dark:text-red-400 hover:underline whitespace-nowrap ml-2 focus:outline-none">
                                                Mark as read
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="formatDate(notification.created_at)"></p>
                                    </div>
                                </template>
                            </div>
                        </x-slot>
                    </x-dropdown>


                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="64">
            
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none transition group">

                                <!-- Divider -->
                                <span class="text-gray-300 dark:text-gray-600">|</span>

                                <!-- Avatar and Name -->
                                <div class="flex items-center gap-2">
                                    <img src="{{ $avatarUrl }}" alt="User Avatar" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600" />
                                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 ml-1">{{ $user->name }}</span>
                                </div>

                                <!-- Dropdown Icon -->
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.23 7.21a1 1 0 011.41.02L10 10.58l3.36-3.35a1 1 0 111.41 1.42l-4.06 4.05a1 1 0 01-1.41 0L5.21 8.63a1 1 0 01.02-1.42z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                <div class="font-medium">{{ $user->name }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                <div class="text-xs uppercase tracking-wide text-indigo-600 dark:text-indigo-400 mt-1">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                            No Role Assigned
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-1 border-gray-200 dark:border-gray-700">

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Page content -->
            <main class="p-8 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function notificationHandler() {
            return {
                open: false,
                unreadCount: 0,
                notifications: [],
                loading: false,
                
                init() {
                    this.fetchNotifications();
                    // Poll for new notifications every 30 seconds
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                    
                    // Listen for new notification events if using websockets/broadcasting
                    window.Echo && window.Echo.private('notifications.' + {{ auth()->id() }})
                        .listen('.notification.created', (e) => {
                            this.unreadCount++;
                            this.notifications.unshift(e.notification);
                        });
                },
                
                toggleNotifications() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },
                
                async fetchNotifications() {
                    try {
                        this.loading = true;
                        const response = await fetch('{{ route("notifications.index") }}');
                        
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        
                        const data = await response.json();
                        
                        this.notifications = data.notifications || [];
                        this.unreadCount = data.unreadCount || 0;
                        
                        console.log('Fetched notifications:', this.notifications);
                        console.log('Unread count:', this.unreadCount);
                    } catch (error) {
                        console.error('Error fetching notifications:', error);
                        // Fallback to empty arrays if there's an error
                        this.notifications = [];
                        this.unreadCount = 0;
                    } finally {
                        this.loading = false;
                    }
                },
                
                // Delete one notification
                async deleteNotification(notificationId) {
                    try {
                        const response = await fetch(`/notifications/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to delete notification');
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.notifications = this.notifications.filter(n => n.id !== notificationId);
                            this.unreadCount = Math.max(0, this.notifications.length);
                        }
                    } catch (error) {
                        console.error('Error deleting notification:', error);
                        // Optimistic update
                        this.notifications = this.notifications.filter(n => n.id !== notificationId);
                        this.unreadCount = Math.max(0, this.notifications.length);
                    }
                },
                
                // Delete all notifications
                async deleteAll() {
                    try {
                        const response = await fetch('/notifications/delete-all', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to delete all notifications');
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.notifications = [];
                            this.unreadCount = 0;
                        }
                    } catch (error) {
                        console.error('Error deleting all notifications:', error);
                        // Optimistic update
                        this.notifications = [];
                        this.unreadCount = 0;
                    }
                },
                
                formatDate(dateString) {
                    try {
                        const date = new Date(dateString);
                        return date.toLocaleString();
                    } catch (error) {
                        return dateString;
                    }
                }
            }
        }
    </script>

</body>
</html>