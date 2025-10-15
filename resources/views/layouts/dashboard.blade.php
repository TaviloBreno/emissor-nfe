<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Emissor NFe')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    <!-- Tailwind Config for Dark Mode -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .sidebar-transition {
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        
        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200" x-data="{ 
    sidebarOpen: true, 
    darkMode: localStorage.getItem('darkMode') === 'true' || false,
    mobileMenuOpen: false
}" x-init="
    $watch('darkMode', value => {
        localStorage.setItem('darkMode', value);
        if (value) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
    
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
    
    // Auto-collapse sidebar on mobile
    if (window.innerWidth < 768) {
        sidebarOpen = false;
    }
    
    window.addEventListener('resize', () => {
        if (window.innerWidth < 768) {
            sidebarOpen = false;
        }
    });
">

    @auth
        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             @click="mobileMenuOpen = false">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 sidebar-transition bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg custom-scrollbar overflow-y-auto"
             :class="{ 
                'w-64': sidebarOpen, 
                'w-16': !sidebarOpen,
                'translate-x-0': mobileMenuOpen || window.innerWidth >= 768,
                '-translate-x-full': !mobileMenuOpen && window.innerWidth < 768
             }">
             
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700 bg-primary-600 dark:bg-primary-700">
                <div class="flex items-center space-x-2 text-white">
                    <i class="fas fa-receipt text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-bold text-lg">Emissor NFe</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-8 px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-3 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 border-r-2 border-primary-600' : '' }}">
                    <i class="fas fa-tachometer-alt text-lg w-5"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-medium">Dashboard</span>
                </a>
                
                <!-- Notas Fiscais -->
                <div x-data="{ notasOpen: {{ request()->routeIs('notas.*') ? 'true' : 'false' }} }">
                    <button @click="notasOpen = !notasOpen" 
                            class="w-full flex items-center justify-between px-3 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group {{ request()->routeIs('notas.*') ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-file-invoice text-lg w-5"></i>
                            <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-medium">Notas Fiscais</span>
                        </div>
                        <i x-show="sidebarOpen" :class="notasOpen ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-xs transition-transform duration-200"></i>
                    </button>
                    
                    <div x-show="notasOpen && sidebarOpen" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="ml-8 mt-2 space-y-1 overflow-hidden">
                        <a href="{{ route('notas.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('notas.index') ? 'text-primary-600 dark:text-primary-400 font-medium' : '' }}">
                            <i class="fas fa-list mr-2"></i>Listar Todas
                        </a>
                        <a href="{{ route('notas.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('notas.create') ? 'text-primary-600 dark:text-primary-400 font-medium' : '' }}">
                            <i class="fas fa-plus mr-2"></i>Nova Nota
                        </a>
                    </div>
                </div>
                
                <!-- Relatórios -->
                <a href="{{ route('relatorios.index') }}" 
                   class="flex items-center px-3 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group {{ request()->routeIs('relatorios.*') ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 border-r-2 border-primary-600' : '' }}">
                    <i class="fas fa-chart-bar text-lg w-5"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-medium">Relatórios</span>
                </a>
                
                <!-- Configurações -->
                <a href="{{ route('configuracoes.index') }}" 
                   class="flex items-center px-3 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group {{ request()->routeIs('configuracoes.*') ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 border-r-2 border-primary-600' : '' }}">
                    <i class="fas fa-cog text-lg w-5"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-medium">Configurações</span>
                </a>
            </nav>
            
            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 dark:bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-transition" :class="{ 'ml-64': sidebarOpen && window.innerWidth >= 768, 'ml-16': !sidebarOpen && window.innerWidth >= 768, 'ml-0': window.innerWidth < 768 }">
            <!-- Top Navigation -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-4">
                        <!-- Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i :class="sidebarOpen ? 'fa-bars' : 'fa-bars'" class="fas"></i>
                        </button>
                        
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <!-- Page Title -->
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">@yield('page-description', '')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" 
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i x-show="!darkMode" class="fas fa-moon"></i>
                            <i x-show="darkMode" class="fas fa-sun"></i>
                        </button>
                        
                        <!-- Notifications -->
                        <div class="relative" x-data="{ notificationsOpen: false, notifications: [], unreadCount: 0 }" 
                             x-init="loadNotifications()"
                             @click.away="notificationsOpen = false">
                            <button @click="notificationsOpen = !notificationsOpen" 
                                    class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-bell"></i>
                                <span x-show="unreadCount > 0" 
                                      class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"
                                      x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="notificationsOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                
                                <!-- Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notificações</h3>
                                        <div class="flex space-x-2">
                                            <button @click="markAllAsRead()" 
                                                    class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500" 
                                                    title="Marcar todas como lidas">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <a href="/notifications" 
                                               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-500"
                                               title="Ver todas">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="p-6 text-center">
                                            <div class="w-12 h-12 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-bell-slash text-gray-400"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer"
                                             @click="openNotification(notification)">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs"
                                                         :class="{
                                                             'bg-green-500': notification.color === 'green',
                                                             'bg-red-500': notification.color === 'red',
                                                             'bg-yellow-500': notification.color === 'yellow',
                                                             'bg-blue-500': notification.color === 'blue' || !notification.color
                                                         }">
                                                        <i :class="notification.icon || 'fas fa-bell'"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white" 
                                                       :class="{ 'font-bold': !notification.read_at }"
                                                       x-text="notification.title"></p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate" 
                                                       x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" 
                                                       x-text="notification.created_at"></p>
                                                </div>
                                                <div x-show="!notification.read_at" 
                                                     class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Footer -->
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="/notifications" 
                                       class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 font-medium">
                                        Ver todas as notificações
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg px-3 py-2 transition-colors">
                                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- User Dropdown -->
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="userMenuOpen = false"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user mr-3"></i>Meu Perfil
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-cog mr-3"></i>Configurações
                                </a>
                                <hr class="border-gray-200 dark:border-gray-700 my-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg animate-fade-in" x-data="{ show: true }" x-show="show">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg animate-fade-in" x-data="{ show: true }" x-show="show">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @else
        <!-- Content for non-authenticated users -->
        @yield('content')
    @endauth

    <!-- Scripts -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    
    <!-- Notifications Scripts -->
    <script>
        function loadNotifications() {
            fetch('/api/notifications/unread')
                .then(response => response.json())
                .then(data => {
                    this.notifications = data.notifications;
                    this.unreadCount = data.count;
                })
                .catch(error => console.log('Error loading notifications:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.notifications.forEach(n => n.read_at = new Date());
                    this.unreadCount = 0;
                }
            });
        }

        function openNotification(notification) {
            // Marcar como lida se não foi lida
            if (!notification.read_at) {
                fetch(`/notifications/${notification.id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });
                notification.read_at = new Date();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
            
            // Redirecionar se tiver URL
            if (notification.url) {
                window.location.href = notification.url;
            }
        }

        // Auto-refresh notifications every 30 seconds
        setInterval(() => {
            if (typeof loadNotifications === 'function') {
                loadNotifications();
            }
        }, 30000);
    </script>
    
    @yield('scripts')
</body>
</html>