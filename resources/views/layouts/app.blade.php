@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            <button @click="open = !open" class="relative focus:outline-none group">
                                <svg class="h-6 w-6 text-gray-600 group-hover:text-indigo-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php $unread = Auth::user()->unreadNotifications; @endphp
                                @if($unread->count() > 0)
                                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.5rem] h-6 shadow-lg animate-pulse z-10">
                                        {{ $unread->count() > 99 ? '99+' : $unread->count() }}
                                    </span>
                                @else
                                    <!-- Test notification badge - always show for testing -->
                                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.5rem] h-6 shadow-lg animate-pulse z-10">
                                        3
                                    </span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto">
                                <div class="p-4 border-b flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Notifications</span>
                                    @if($unread->count() > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.5rem] h-6">
                                            {{ $unread->count() > 99 ? '99+' : $unread->count() }}
                                        </span>
                                    @endif
                                </div>
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
                    <div class="md:hidden ml-2 flex items-center space-x-2">
                        @auth
                            @php $mobileUnread = Auth::user()->unreadNotifications; @endphp
                            @if($mobileUnread->count() > 0)
                                <div class="relative">
                                    <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.25rem] h-5">
                                        {{ $mobileUnread->count() > 99 ? '99+' : $mobileUnread->count() }}
                                    </span>
                                </div>
                            @endif
                        @endauth
                        <button @click="open = !open" x-data="{ open: false }" class="text-gray-700 hover:text-indigo-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <nav x-data="{ open: false }" x-show="open" x-cloak class="md:hidden mt-2 space-y-1 bg-white border-t border-gray-200 p-4 rounded shadow">
                @auth
                    @php $mobileMenuUnread = Auth::user()->unreadNotifications; @endphp
                    @if($mobileMenuUnread->count() > 0)
                        <div class="flex items-center justify-between px-3 py-2 mb-2 bg-red-50 border border-red-200 rounded-lg">
                            <span class="text-sm font-medium text-red-700">Notifications</span>
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[1.5rem] h-6">
                                {{ $mobileMenuUnread->count() > 99 ? '99+' : $mobileMenuUnread->count() }}
                            </span>
                        </div>
                    @endif
                @endauth
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

<!-- Gemini Chatbot Widget -->
<div x-data="chatbotWidget()" x-init="init()" class="fixed bottom-6 right-6 z-50">
    <div x-show="open" x-transition class="w-80 bg-white shadow-xl rounded-lg border border-gray-200 flex flex-col">
        <div class="flex items-center justify-between px-4 py-2 border-b">
            <span class="font-semibold text-indigo-700">AI Chatbot</span>
            <button @click="open = false" class="text-gray-400 hover:text-gray-700">&times;</button>
        </div>
        <div class="flex-1 overflow-y-auto px-4 py-2 space-y-2" style="max-height: 300px;">
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.user ? 'text-right' : 'text-left'">
                    <div :class="msg.user ? 'bg-indigo-100 text-indigo-800 ml-16' : 'bg-gray-100 text-gray-700 mr-16'" class="inline-block px-3 py-2 rounded-lg mb-1">
                        <span x-text="msg.text"></span>
                    </div>
                </div>
            </template>
            <template x-if="loading">
                <div class="text-left">
                    <div class="inline-block px-3 py-2 rounded-lg bg-gray-200 text-gray-500 animate-pulse">AI is typing...</div>
                </div>
            </template>
        </div>
        <form @submit.prevent="sendMessage" class="flex items-center border-t px-2 py-2">
            <input x-model="input" type="text" class="flex-1 border rounded px-2 py-1 text-sm focus:outline-none" placeholder="Ask me anything..." :disabled="loading">
            <button type="submit" class="ml-2 px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm" :disabled="loading || !input.trim()">Send</button>
        </form>
    </div>
    <button @click="open = !open" x-show="!open" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center focus:outline-none">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/><circle cx="12" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 20h.01"/></svg>
    </button>
</div>
<script>
console.log('Chatbot widget loaded');
function chatbotWidget() {
    return {
        open: false,
        input: '',
        messages: [],
        loading: false,
        id: 0,
        init() {},
        async sendMessage() {
            if (!this.input.trim()) return;
            const userMsg = { id: ++this.id, text: this.input, user: true };
            this.messages.push(userMsg);
            const inputText = this.input;
            this.input = '';
            this.loading = true;
            try {
                console.log('Sending to Gemini:', inputText);
                const res = await fetch('/gemini-chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ message: inputText })
                });
                console.log('Received response:', res);
                const data = await res.json();
                this.messages.push({ id: ++this.id, text: data.reply || 'No response from AI.', user: false });
            } catch (e) {
                console.error('Chatbot fetch error:', e);
                this.messages.push({ id: ++this.id, text: 'Error contacting AI.', user: false });
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
