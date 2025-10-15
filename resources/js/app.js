require('./bootstrap');

// Função para alternar visibilidade da senha
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = document.querySelector(`[onclick="togglePassword('${fieldId}')"]`);
    
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
        loginTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
        loginTab.classList.add('text-gray-500');
        registerTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
        registerTab.classList.remove('text-gray-500');
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
    } else {
        registerTab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
        registerTab.classList.add('text-gray-500');
        loginTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
        loginTab.classList.remove('text-gray-500');
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
    }
}

// Disponibilizar funções globalmente
window.togglePassword = togglePassword;
window.toggleTab = toggleTab;
