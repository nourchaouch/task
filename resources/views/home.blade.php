<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tâches - Accueil</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and primary nav -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home.index') }}" class="text-xl font-bold text-blue-600">
                            <i class="fas fa-tasks mr-2"></i>
                            TaskManager
                        </a>
                    </div>

                    <!-- Primary Navigation -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('home.index') }}"
                           class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-home mr-1"></i> Accueil
                        </a>
                        <a href="{{ route('projects.index') }}"
                           class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-project-diagram mr-1"></i> Projets
                        </a>
                        <a href="{{ route('tasks.index') }}"
                           class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-clipboard-list mr-1"></i> Tâches
                        </a>
                        <a href="{{ route('calendar') }}"
                           class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-calendar mr-1"></i> Calendrier
                        </a>
                    </div>
                </div>

                <!-- Right side navigation -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Notifications -->
                    <div class="ml-3 relative">
                        <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Voir les notifications</span>
                            <i class="fas fa-bell text-xl"></i>
                            @if($notificationCount > 0)
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                            @endif
                        </button>
                    </div>

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            @auth
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <button class="flex text-sm rounded-full focus:outline-none" id="user-menu-button">
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                </button>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Connexion
                                </a>
                                <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-user-plus mr-1"></i> Inscription
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home.index') }}" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <a href="{{ route('projects.index') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-project-diagram mr-1"></i> Projets
                </a>
                <a href="{{ route('tasks.index') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-clipboard-list mr-1"></i> Tâches
                </a>
                <a href="{{ route('calendar') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-calendar mr-1"></i> Calendrier
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Bienvenue sur TaskManager
            </h1>
            <p class="text-gray-600">
                Gérez efficacement vos projets et tâches en équipe.
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <a href="{{ route('projects.create') }}" class="flex items-center text-blue-600 hover:text-blue-700">
                    <i class="fas fa-plus-circle text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold">Nouveau Projet</h3>
                        <p class="text-sm text-gray-600">Créer un nouveau projet</p>
                    </div>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <a href="{{ route('tasks.create') }}" class="flex items-center text-green-600 hover:text-green-700">
                    <i class="fas fa-tasks text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold">Nouvelle Tâche</h3>
                        <p class="text-sm text-gray-600">Ajouter une nouvelle tâche</p>
                    </div>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <a href="{{ route('calendar') }}" class="flex items-center text-purple-600 hover:text-purple-700">
                    <i class="fas fa-calendar-plus text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold">Planifier</h3>
                        <p class="text-sm text-gray-600">Gérer le calendrier</p>
                    </div>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <a href="{{ route('teams.index') }}" class="flex items-center text-orange-600 hover:text-orange-700">
                    <i class="fas fa-users text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold">Équipes</h3>
                        <p class="text-sm text-gray-600">Gérer les équipes</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Activité Récente</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 text-sm">Voir tout</a>
            </div>

            @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}" alt="">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-900">
                                    <a href="#" class="font-medium text-gray-900">{{ $activity->user->name }}</a>
                                    {{ $activity->description }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
            @endif
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
