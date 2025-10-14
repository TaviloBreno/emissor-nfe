<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Emissor NFe</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card de Login -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-receipt text-white text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Emissor NFe</h1>
                <p class="text-blue-100">Sistema de Notas Fiscais Eletrônicas</p>
            </div>

            <!-- Form Container -->
            <div class="p-8">
                <!-- Tabs -->
                <div class="flex mb-6" id="authTabs">
                    <button class="flex-1 py-2 text-center font-medium border-b-2 border-blue-600 text-blue-600 tab-btn active" 
                            onclick="switchTab('login')">
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                    </button>
                    <button class="flex-1 py-2 text-center font-medium border-b-2 border-gray-200 text-gray-400 tab-btn" 
                            onclick="switchTab('register')">
                        <i class="fas fa-user-plus mr-2"></i>Cadastrar
                    </button>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('auth.login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope mr-2"></i>E-mail
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="seu@email.com"
                               required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-12"
                                   placeholder="••••••••"
                                   required>
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('password')">
                                <i class="fas fa-eye text-gray-400" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Lembrar-me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Esqueceu a senha?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 transition duration-200 font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                    </button>
                </form>

                <!-- Register Form (Hidden by default) -->
                <form id="registerForm" method="POST" action="{{ route('auth.register') }}" class="space-y-6 hidden">
                    @csrf
                    
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="reg_name" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-user mr-2"></i>Nome Completo
                        </label>
                        <input type="text" 
                               id="reg_name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Seu nome completo">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="reg_email" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope mr-2"></i>E-mail
                        </label>
                        <input type="email" 
                               id="reg_email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="seu@email.com">
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="reg_password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2"></i>Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="reg_password" 
                                   name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-12"
                                   placeholder="••••••••">
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('reg_password')">
                                <i class="fas fa-eye text-gray-400" id="regPasswordToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2"></i>Confirmar Senha
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="reg_password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-12"
                                   placeholder="••••••••">
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('reg_password_confirmation')">
                                <i class="fas fa-eye text-gray-400" id="regPasswordConfirmToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-4 rounded-lg hover:from-green-700 hover:to-blue-700 focus:ring-4 focus:ring-green-200 transition duration-200 font-medium">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white">
            <p class="text-sm">© 2025 Emissor NFe - Todos os direitos reservados</p>
            <p class="text-xs mt-2 opacity-75">Desenvolvido com Laravel e Tailwind CSS</p>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const tabButtons = document.querySelectorAll('.tab-btn');
            
            // Reset all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-600', 'text-blue-600');
                btn.classList.add('border-gray-200', 'text-gray-400');
            });
            
            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabButtons[0].classList.add('active', 'border-blue-600', 'text-blue-600');
                tabButtons[0].classList.remove('border-gray-200', 'text-gray-400');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                tabButtons[1].classList.add('active', 'border-blue-600', 'text-blue-600');
                tabButtons[1].classList.remove('border-gray-200', 'text-gray-400');
            }
        }
        
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + 'ToggleIcon') || document.getElementById('passwordToggleIcon');
            
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
        
        // Show register form if there are register errors
        @if($errors->any() && old('name'))
            switchTab('register');
        @endif
    </script>
</body>
</html>