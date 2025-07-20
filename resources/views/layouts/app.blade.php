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
                            @else
                                <a href="{{ $user->role === 'project_manager' ? route('dashboard.manager') : route('dashboard.member') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('dashboard.manager') || request()->routeIs('dashboard.member')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                                <a href="{{ url('/projects') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('projects*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Projects</a>
                                <a href="{{ url('/tasks') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('tasks*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Tasks</a>
                                <a href="{{ url('/members') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->is('members*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Members</a>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('login')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Login</a>
                            <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium @if(request()->routeIs('register')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Register</a>
                        @endguest
                    </nav>
                    @auth
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
                    @else
                        <a href="{{ $user->role === 'project_manager' ? route('dashboard.manager') : route('dashboard.member') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->routeIs('dashboard.manager') || request()->routeIs('dashboard.member')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Dashboard</a>
                        <a href="{{ url('/projects') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('projects*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Projects</a>
                        <a href="{{ url('/tasks') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('tasks*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Tasks</a>
                        <a href="{{ url('/members') }}" class="block px-3 py-2 rounded-md text-base font-medium @if(request()->is('members*')) bg-indigo-100 text-indigo-700 font-bold @else text-gray-700 hover:bg-indigo-50 @endif">Members</a>
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

</body>
</html>
