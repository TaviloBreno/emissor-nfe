<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Emissor NFe</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0 relative">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-receipt text-2xl text-blue-400 mr-3"></i>
                    <h1 class="text-xl font-bold">Emissor NFe</h1>
                </div>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center py-2.5 px-6 bg-gray-900 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('notas.index') }}" class="flex items-center py-2.5 px-6 hover:bg-gray-700 transition">
                    <i class="fas fa-file-invoice mr-3"></i>
                    Notas Fiscais
                </a>
                <a href="{{ route('notas.create') }}" class="flex items-center py-2.5 px-6 hover:bg-gray-700 transition">
                    <i class="fas fa-plus mr-3"></i>
                    Nova Nota
                </a>
                <a href="#" class="flex items-center py-2.5 px-6 hover:bg-gray-700 transition">
                    <i class="fas fa-users mr-3"></i>
                    Clientes
                </a>
                <a href="#" class="flex items-center py-2.5 px-6 hover:bg-gray-700 transition">
                    <i class="fas fa-box mr-3"></i>
                    Produtos
                </a>
                <a href="#" class="flex items-center py-2.5 px-6 hover:bg-gray-700 transition">
                    <i class="fas fa-cog mr-3"></i>
                    Configurações
                </a>
            </nav>

            <!-- Logout no rodapé do sidebar -->
            <div class="absolute bottom-6 left-0 right-0 px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center py-2.5 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition rounded-lg">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6">
                <!-- Cards de Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total de Notas -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total de Notas</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($stats['total_notas']) }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-file-invoice mr-1"></i>Todas as notas
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Notas Aprovadas -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Aprovadas</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['notas_aprovadas']) }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>Aprovadas pela SEFAZ
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Notas Pendentes -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Pendentes</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($stats['notas_pendentes']) }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>Aguardando processamento
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-2xl text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Valor Total -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Valor Total</p>
                                <p class="text-3xl font-bold text-purple-600 mt-2">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-dollar-sign mr-1"></i>Notas autorizadas
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-2xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos e Relatórios -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Gráfico de Status -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>Distribuição por Status
                        </h3>
                        <div class="relative h-64">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico Mensal -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-line mr-2 text-green-600"></i>Evolução Mensal
                        </h3>
                        <div class="relative h-64">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Notas Recentes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-history mr-2 text-gray-600"></i>Notas Recentes
                            </h3>
                            <a href="{{ route('notas.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($notasRecentes->count() > 0)
                            <div class="space-y-4">
                                @foreach($notasRecentes as $nota)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                                {{ $nota->status === 'autorizada' ? 'bg-green-100' : '' }}
                                                {{ $nota->status === 'rascunho' ? 'bg-yellow-100' : '' }}
                                                {{ $nota->status === 'cancelada' ? 'bg-red-100' : '' }}">
                                                <i class="fas fa-file-invoice 
                                                    {{ $nota->status === 'autorizada' ? 'text-green-600' : '' }}
                                                    {{ $nota->status === 'rascunho' ? 'text-yellow-600' : '' }}
                                                    {{ $nota->status === 'cancelada' ? 'text-red-600' : '' }}"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Nota #{{ $nota->numero }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $nota->created_at->format('d/m/Y H:i') }} • 
                                                    <span class="capitalize 
                                                        {{ $nota->status === 'autorizada' ? 'text-green-600' : '' }}
                                                        {{ $nota->status === 'rascunho' ? 'text-yellow-600' : '' }}
                                                        {{ $nota->status === 'cancelada' ? 'text-red-600' : '' }}">
                                                        {{ $nota->status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</div>
                                            <a href="{{ route('notas.show', $nota->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                                Ver detalhes
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-file-invoice text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-500 mb-4">Nenhuma nota fiscal encontrada</p>
                                <a href="{{ route('notas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Criar primeira nota
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Dados dos gráficos
        const statusData = @json($distribuicaoStatus);
        const monthlyData = @json($estatisticasMensais);

        // Gráfico de Status (Donut)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData).map(status => {
                    const statusMap = {
                        'autorizada': 'Autorizada',
                        'rascunho': 'Rascunho',
                        'cancelada': 'Cancelada',
                        'rejeitada': 'Rejeitada'
                    };
                    return statusMap[status] || status;
                }),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#10B981', // Verde para autorizada
                        '#F59E0B', // Amarelo para rascunho
                        '#EF4444', // Vermelho para cancelada
                        '#6B7280'  // Cinza para rejeitada
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Gráfico Mensal (Linha)
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.mes),
                datasets: [{
                    label: 'Notas Emitidas',
                    data: monthlyData.map(item => item.total_notas),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            color: '#F3F4F6'
                        }
                    }
                }
            }
        });

        // Atualizar dados a cada 30 segundos (opcional)
        setInterval(() => {
            // Aqui poderia haver uma chamada AJAX para atualizar os dados
            // location.reload(); // Por enquanto, apenas recarrega a página
        }, 30000);
    </script>
</body>
</html>
