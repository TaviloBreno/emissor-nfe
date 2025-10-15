@extends('layouts.auth')

@section('title', 'Login - Emissor NFe')

@section('content')
<div class="w-full max-w-md animate-fade-in-up">
    <!-- Card de Login -->
    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
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
        <div class="flex border-b border-gray-200 bg-gray-50 animate-fade-in-up-delay-2">
            <button id="login-tab" 
                    onclick="toggleTab('login')" 
                    class="flex-1 py-4 px-6 text-center font-medium border-b-2 border-blue-500 text-blue-600 transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Entrar
            </button>
            <button id="register-tab" 
                    onclick="toggleTab('register')" 
                    class="flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 transition-all">
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
                        <label for="login-email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>E-mail
                        </label>
                        <input type="email" 
                               id="login-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="Digite seu e-mail"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="login-password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="login-password" 
                                   name="password" 
                                   required
                                   placeholder="Digite sua senha"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                            <button type="button" 
                                    onclick="togglePassword('login-password', this)" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-gray-600">Lembrar-me</span>
                        </label>
                        <a href="#" class="text-blue-600 hover:text-blue-500 transition-colors">
                            Esqueceu a senha?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="hidden animate-fade-in-up-delay-2">
                <form method="POST" action="{{ route('auth.register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="register-name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nome Completo
                        </label>
                        <input type="text" 
                               id="register-name" 
                               name="name" 
                               required 
                               value="{{ old('name') }}"
                               placeholder="Digite seu nome completo"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="register-email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>E-mail
                        </label>
                        <input type="email" 
                               id="register-email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="Digite seu e-mail"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="register-password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="register-password" 
                                   name="password" 
                                   required
                                   placeholder="Digite sua senha"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                            <button type="button" 
                                    onclick="togglePassword('register-password', this)" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Confirmar Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="register-password-confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Confirme sua senha"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all">
                            <button type="button" 
                                    onclick="togglePassword('register-password-confirmation', this)" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Cadastrar
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-600 border-t border-gray-100">
            <p>&copy; 2024 Emissor NFe. Todos os direitos reservados.</p>
        </div>
    </div>
</div>

<script>
function toggleTab(tab) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    
    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
        loginTab.classList.remove('text-gray-500');
        registerTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
        registerTab.classList.add('text-gray-500');
    } else {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        registerTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
        registerTab.classList.remove('text-gray-500');
        loginTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
        loginTab.classList.add('text-gray-500');
    }
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection