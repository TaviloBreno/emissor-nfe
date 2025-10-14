<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Emissor NFe')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('notas.index') }}" class="flex items-center space-x-2 text-white">
                        <i class="fas fa-receipt text-xl"></i>
                        <span class="font-bold text-xl">Emissor NFe</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('notas.index') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-list mr-1"></i>
                            Notas Fiscais
                        </a>
                        <a href="{{ route('notas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Nova Nota
                        </a>
                        <div class="text-white">
                            <i class="fas fa-user mr-1"></i>
                            {{ auth()->user()->name }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            Entrar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4">
            <div class="text-center">
                <p class="text-sm">
                    © {{ date('Y') }} Emissor NFe - Sistema de Notas Fiscais Eletrônicas
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Desenvolvido com Laravel e Tailwind CSS
                </p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>