@extends('layouts.auth')

@section('title', 'Login - Emissor NFe')

@section('content')
<div class="w-full max-w-md animate-fade-in-up">
    <!-- Card de Login -->
    <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-center animate-fade-in-up-delay-1">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-receipt text-2xl text-white"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Bem-vindo</h1>
            <p class="text-blue-100">Sistema de Notas Fiscais Eletrônicas</p>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 animate-fade-in-up-delay-2">
            <button id="login-tab" 
                    onclick="toggleTab('login')" 
                    class="flex-1 py-4 px-6 text-center font-medium border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Entrar
            </button>
            <button id="register-tab" 
                    onclick="toggleTab('register')" 
                    class="flex-1 py-4 px-6 text-center font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all">
                <i class="fas fa-user-plus mr-2"></i>Cadastrar
            </button>
        </div>

        <!-- Forms Container -->
        <div class="p-6 sm:p-8">
            <!-- Login Form -->
            <div id="login-form" class="animate-fade-in-up-delay-2">
                <form method="POST" action="{{ route('auth.login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="login-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>E-mail
                        </label>
                        <input type="email" 
                               id="login-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                               placeholder="seu@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="login-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="login-password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                                   placeholder="Sua senha">
                            <button type="button" 
                                    onclick="togglePassword('login-password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Lembrar-me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                            Esqueci minha senha
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="hidden">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="register-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nome Completo
                        </label>
                        <input type="text" 
                               id="register-name" 
                               name="name" 
                               required 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                               placeholder="Seu nome completo">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="register-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>E-mail
                        </label>
                        <input type="email" 
                               id="register-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                               placeholder="seu@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="register-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="register-password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                                   placeholder="Mínimo 8 caracteres">
                            <button type="button" 
                                    onclick="togglePassword('register-password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password-confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Confirmar Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password-confirmation" 
                                   name="password_confirmation" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all"
                                   placeholder="Confirme sua senha">
                            <button type="button" 
                                    onclick="togglePassword('password-confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center animate-fade-in-up-delay-2">
        <p class="text-white/80 text-sm">
            © {{ date('Y') }} Emissor NFe - Sistema de Notas Fiscais Eletrônicas
        </p>
        <p class="text-white/60 text-xs mt-2">
            Desenvolvido com Laravel e Tailwind CSS
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Função para alternar visibilidade da senha
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = document.querySelector(`button[onclick="togglePassword('${fieldId}')"]`);
        
        if (field.type === 'password') {
            field.type = 'text';
            button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path></svg>';
        } else {
            field.type = 'password';
            button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
        }
    }

    // Função para alternar entre tabs
    function toggleTab(tabName) {
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        if (tabName === 'register') {
            loginTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            loginTab.classList.add('text-gray-500', 'dark:text-gray-400');
            registerTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            registerTab.classList.remove('text-gray-500', 'dark:text-gray-400');
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        } else {
            registerTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            registerTab.classList.add('text-gray-500', 'dark:text-gray-400');
            loginTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            loginTab.classList.remove('text-gray-500', 'dark:text-gray-400');
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        }
    }

    // Auto-focus no primeiro campo quando carrega a página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('login-email').focus();
    });
</script>
@endsection