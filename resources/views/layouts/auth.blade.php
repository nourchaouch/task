<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TaskManager') }} - @yield('title', 'Authentication')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home.index') }}" class="text-xl font-bold text-blue-600">
                            <i class="fas fa-tasks mr-2"></i>
                            TaskManager
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-white mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} TaskManager. Tous droits réservés.
            </p>
        </div>
    </footer>

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
                    const res = await fetch('/gemini-chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ message: inputText })
                    });
                    const data = await res.json();
                    this.messages.push({ id: ++this.id, text: data.reply || 'No response from AI.', user: false });
                } catch (e) {
                    this.messages.push({ id: ++this.id, text: 'Error contacting AI.', user: false });
                } finally {
                    this.loading = false;
                }
            }
        }
    }
    </script>
</body>
</html>
