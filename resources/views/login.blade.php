@extends('layouts.auth')

@section('title', 'Login - Emissor NFe')

@section('content')
<div class="w-full max-w-md animate-fade-in-up">
    <!-- Card de Login -->
    <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-6 text-center animate-fade-in-up-delay-1">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-white/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-2xl text-white"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Bem-vindo</h1>
            <p class="text-blue-100">Sistema de Notas Fiscais Eletrônicas</p>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 bg-gray-50 animate-fade-in-up-delay-2">
            <button id="login-tab" 
                    onclick="toggleTab('login')" 
                    class="flex-1 py-4 px-6 text-center font-medium border-b-2 border-blue-600 text-blue-700 bg-white transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Entrar
            </button>
            <button id="register-tab" 
                    onclick="toggleTab('register')" 
                    class="flex-1 py-4 px-6 text-center font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-all">
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
                        <label for="login-email" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>E-mail
                        </label>
                        <input type="email" 
                               id="login-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                               placeholder="seu@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="login-password" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="login-password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                                   placeholder="Sua senha">
                            <button type="button" 
                                    onclick="togglePassword('login-password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-600 bg-white">
                            <span class="ml-2 text-sm font-medium text-gray-800">Lembrar-me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-700 hover:text-blue-800 font-medium transition-colors">
                            Esqueci minha senha
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
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
                        <label for="register-name" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Nome Completo
                        </label>
                        <input type="text" 
                               id="register-name" 
                               name="name" 
                               required 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                               placeholder="Seu nome completo">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="register-email" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>E-mail
                        </label>
                        <input type="email" 
                               id="register-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                               placeholder="seu@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="register-password" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="register-password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                                   placeholder="Mínimo 8 caracteres">
                            <button type="button" 
                                    onclick="togglePassword('register-password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password-confirmation" class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Confirmar Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password-confirmation" 
                                   name="password_confirmation" 
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white text-gray-900 placeholder-gray-500 transition-all shadow-sm"
                                   placeholder="Confirme sua senha">
                            <button type="button" 
                                    onclick="togglePassword('password-confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-700 to-blue-700 hover:from-green-800 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center animate-fade-in-up-delay-2">
        <p class="text-white text-sm font-medium shadow-sm">
            © {{ date('Y') }} Emissor NFe - Sistema de Notas Fiscais Eletrônicas
        </p>
        <p class="text-white/90 text-xs mt-2">
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
            loginTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-700', 'bg-white');
            loginTab.classList.add('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
            registerTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-700', 'bg-white');
            registerTab.classList.remove('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        } else {
            registerTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-700', 'bg-white');
            registerTab.classList.add('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
            loginTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-700', 'bg-white');
            loginTab.classList.remove('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
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