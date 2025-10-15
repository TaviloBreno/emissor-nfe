@extends('layouts.dashboard')

@section('title', 'Configurações - Emissor NFe')

@section('page-title', 'Configurações')
@section('page-description', 'Gerencie suas configurações do sistema')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Tabs Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
            <div class="border-b border-gray-100 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" id="config-tabs">
                    <button onclick="showTab('perfil')" 
                            class="tab-button py-4 px-1 border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-medium text-sm">
                        <i class="fas fa-user mr-2"></i>Perfil
                    </button>
                    <button onclick="showTab('emitente')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm">
                        <i class="fas fa-building mr-2"></i>Emitente
                    </button>
                    <button onclick="showTab('sistema')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm">
                        <i class="fas fa-cog mr-2"></i>Sistema
                    </button>
                    <button onclick="showTab('seguranca')" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm">
                        <i class="fas fa-shield-alt mr-2"></i>Segurança
                    </button>
                </nav>
            </div>
        </div>

        <!-- Perfil Tab -->
        <div id="perfil-tab" class="tab-content">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-20 h-20 bg-blue-600 dark:bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>

                <form action="{{ route('configuracoes.profile') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Salvar Perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Emitente Tab -->
        <div id="emitente-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Dados do Emitente</h3>
                
                <form action="{{ route('configuracoes.emitente') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Razão Social</label>
                            <input type="text" 
                                   name="razao_social" 
                                   value="{{ old('razao_social', 'Empresa de Exemplo LTDA') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CNPJ</label>
                            <input type="text" 
                                   name="cnpj" 
                                   value="{{ old('cnpj', '12.345.678/0001-90') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Inscrição Estadual</label>
                            <input type="text" 
                                   name="inscricao_estadual" 
                                   value="{{ old('inscricao_estadual', '123456789') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                            <input type="text" 
                                   name="endereco" 
                                   value="{{ old('endereco', 'Rua das Flores, 123 - Centro') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefone</label>
                            <input type="text" 
                                   name="telefone" 
                                   value="{{ old('telefone', '(11) 99999-9999') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Emitente</label>
                            <input type="email" 
                                   name="email_emitente" 
                                   value="{{ old('email_emitente', 'empresa@exemplo.com') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Salvar Dados do Emitente
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sistema Tab -->
        <div id="sistema-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações do Sistema</h3>
                
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ambiente SEFAZ</label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option>Homologação</option>
                                <option>Produção</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Série NFe</label>
                            <input type="number" 
                                   value="1"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Numeração Atual</label>
                            <input type="number" 
                                   value="1"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timeout (segundos)</label>
                            <input type="number" 
                                   value="30"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Preferências de Email</h4>
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enviar NFe por email automaticamente</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Notificar sobre erros de transmissão</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Relatório diário de vendas</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Segurança Tab -->
        <div id="seguranca-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações de Segurança</h3>
                
                <!-- Alterar Senha -->
                <form action="{{ route('configuracoes.password') }}" method="POST" class="space-y-6 mb-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Alterar Senha</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha Atual</label>
                                <input type="password" 
                                       name="current_password"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nova Senha</label>
                                    <input type="password" 
                                           name="password"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirmar Nova Senha</label>
                                    <input type="password" 
                                           name="password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-key mr-2"></i>Alterar Senha
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Certificado Digital -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Certificado Digital</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Certificado A1</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Válido até: 15/12/2024</p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">
                                Ativo
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="btn-secondary">
                                <i class="fas fa-upload mr-2"></i>Enviar Novo
                            </button>
                            <button class="btn-secondary">
                                <i class="fas fa-download mr-2"></i>Backup
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sessões Ativas -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Sessões Ativas</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-desktop text-blue-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Windows 10 - Chrome</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Atual - 127.0.0.1</p>
                                </div>
                            </div>
                            <span class="text-xs text-green-600 dark:text-green-400">Ativa</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Mobile - Safari</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Há 2 horas - 192.168.1.1</p>
                                </div>
                            </div>
                            <button class="text-xs text-red-600 dark:text-red-400 hover:underline">Revogar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
        btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
    event.target.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
}
</script>
@endsection