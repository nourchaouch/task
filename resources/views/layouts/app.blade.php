@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des tâches')</title>
    <!-- Remove Bootstrap CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Tailwind UI Header --}}
    <header class="bg-white shadow mb-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home.index') }}" class="text-xl font-bold text-indigo-600">TaskManager</a>
                </div>
                <div class="flex items-center">
                    <nav class="hidden md:flex space-x-4">
                        <a href="{{ route('home.index') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('home.index')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Home</a>
                        @auth
                            @php $user = Auth::user(); @endphp
                            @if($user->role === 'admin')
                                <a href="{{ url('/admin') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('admin')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                                <a href="{{ url('/admin/users') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('admin/users*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Manage Users</a>
                                <a href="{{ url('/admin/password') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('admin/password')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Change Password</a>
                                <a href="{{ url('/members') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('members*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Members</a>
                            @else
                                <a href="{{ $user->role === 'project_manager' ? route('dashboard.manager') : route('dashboard.member') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('dashboard.manager') || request()->routeIs('dashboard.member')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                                <a href="{{ url('/projects') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('projects*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Projects</a>
                                <a href="{{ url('/tasks') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('tasks*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Tasks</a>
                                <a href="{{ url('/events') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('events*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Events</a>
                                <a href="{{ route('calendar.index') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('calendar.index')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Calendrier</a>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('login')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Login</a>
                            <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('register')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Register</a>
                        @endguest
                    </nav>
                    @auth
                        <div class="relative mr-4" x-data="{ open: false }">
                            <button @click="open = !open" class="relative focus:outline-none">
                                <svg class="h-6 w-6 text-gray-600 hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php $unread = Auth::user()->unreadNotifications; @endphp
                                @if($unread->count() > 0)
                                    <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto">
                                <div class="p-4 border-b font-semibold text-gray-700">Notifications</div>
                                @if($unread->count() === 0)
                                    <div class="p-4 text-gray-500 text-sm">Aucune notification non lue.</div>
                                @else
                                    <ul>
                                        @foreach($unread as $notification)
                                            <li class="border-b last:border-0">
                                                @if(isset($notification->data['task_id']))
                                                    <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="block px-4 py-2 hover:bg-indigo-50">
                                                        <span class="font-medium">Tâche :</span> {{ $notification->data['title'] }}<br>
                                                        <span class="text-xs text-gray-500">Échéance : {{ \Carbon\Carbon::parse($notification->data['due_date'])->format('d/m/Y H:i') }}</span>
                                                    </a>
                                                @elseif(isset($notification->data['event_id']))
                                                    <a href="{{ route('events.show', $notification->data['event_id']) }}" class="block px-4 py-2 hover:bg-indigo-50">
                                                        <span class="font-medium">Événement :</span> {{ $notification->data['title'] }}<br>
                                                        <span class="text-xs text-gray-500">Date : {{ \Carbon\Carbon::parse($notification->data['date'])->format('d/m/Y H:i') }}</span>
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <form method="POST" action="{{ route('notifications.markAllRead') }}" class="p-2 text-right">
                                    @csrf
                                    <button type="submit" class="text-xs text-indigo-600 hover:underline">Tout marquer comme lu</button>
                                </form>
                            </div>
                        </div>
                        <div class="ml-4 relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-indigo-700 focus:outline-none">
                                <span>{{ $user->name }}</span>
                                <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs capitalize">{{ $user->role }}</span>
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                    <!-- Mobile menu button -->
                    <div class="md:hidden ml-2 flex items-center">
                        <button @click="open = !open" x-data="{ open: false }" class="text-gray-700 hover:text-indigo-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <nav x-data="{ open: false }" x-show="open" x-cloak class="md:hidden mt-2 space-y-1 bg-white border-t border-gray-200 p-4 rounded shadow">
                <a href="{{ route('home.index') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('home.index')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Home</a>
                @auth
                    @if($user->role === 'admin')
                        <a href="{{ url('/admin') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('admin')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                        <a href="{{ url('/admin/users') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('admin/users*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Manage Users</a>
                        <a href="{{ url('/admin/password') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('admin/password')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Change Password</a>
                        <a href="{{ url('/members') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('members*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Members</a>
                    @else
                        <a href="{{ $user->role === 'project_manager' ? route('dashboard.manager') : route('dashboard.member') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('dashboard.manager') || request()->routeIs('dashboard.member')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                        <a href="{{ url('/projects') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('projects*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Projects</a>
                        <a href="{{ url('/tasks') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('tasks*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Tasks</a>
                        <a href="{{ url('/events') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('events*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Events</a>
                        <a href="{{ route('calendar.index') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('calendar.index')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Calendrier</a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('login')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('register')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Register</a>
                @endguest
            </nav>
        </div>
    </header>
    {{-- End Tailwind UI Header --}}

    {{-- Main Content --}}
    @yield('content')

    @stack('scripts')

</body>
</html>
