@extends('layouts.dashboard')

@section('title', 'Dashboard - Emissor NFe')

@section('page-title', 'Dashboard')
@section('page-description', 'Visão geral do seu sistema de notas fiscais')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="lg:hidden">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Visão geral do seu sistema de notas fiscais</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Total Notas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total de Notas</p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($stats['total_notas']) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-file-invoice mr-1"></i>Todas as notas
                        </p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-xl sm:text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Notas Aprovadas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Aprovadas</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($stats['notas_aprovadas']) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>Aprovadas pela SEFAZ
                        </p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl sm:text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Notas Rascunho -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Rascunhos</p>
                        <p class="text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ number_format($stats['notas_pendentes']) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-edit mr-1"></i>Em elaboração
                        </p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-xl sm:text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <!-- Valor Total -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Valor Total</p>
                        <p class="text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-dollar-sign mr-1"></i>Notas autorizadas
                        </p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 dark:bg-purple-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xl sm:text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Reports -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <!-- Status Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 animate-fade-in">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-chart-pie mr-2 text-blue-600 dark:text-blue-400"></i>Distribuição por Status
                </h3>
                <div class="relative h-48 sm:h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 animate-fade-in">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-chart-line mr-2 text-green-600 dark:text-green-400"></i>Tendência Mensal
                </h3>
                <div class="relative h-48 sm:h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 dark:border-gray-700 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 sm:mb-0">
                    <i class="fas fa-clock mr-2 text-blue-600 dark:text-blue-400"></i>Atividades Recentes
                </h3>
                <a href="{{ route('notas.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if($notasRecentes && $notasRecentes->count() > 0)
                <div class="space-y-4">
                    @foreach($notasRecentes as $nota)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-medium
                                            {{ $nota->status === 'autorizada' ? 'bg-green-500' : '' }}
                                            {{ $nota->status === 'rascunho' ? 'bg-yellow-500' : '' }}
                                            {{ $nota->status === 'cancelada' ? 'bg-red-500' : '' }}
                                            {{ $nota->status === 'rejeitada' ? 'bg-gray-500' : '' }}
                                            {{ $nota->status === 'assinada' ? 'bg-blue-500' : '' }}">
                                    <i class="fas 
                                              {{ $nota->status === 'autorizada' ? 'fa-check' : '' }}
                                              {{ $nota->status === 'rascunho' ? 'fa-edit' : '' }}
                                              {{ $nota->status === 'cancelada' ? 'fa-times' : '' }}
                                              {{ $nota->status === 'rejeitada' ? 'fa-ban' : '' }}
                                              {{ $nota->status === 'assinada' ? 'fa-signature' : '' }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">Nota Fiscal #{{ $nota->numero }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                     {{ $nota->status === 'autorizada' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                                     {{ $nota->status === 'rascunho' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                                     {{ $nota->status === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}
                                                     {{ $nota->status === 'rejeitada' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' : '' }}
                                                     {{ $nota->status === 'assinada' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}">
                                            {{ ucfirst($nota->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $nota->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-invoice text-gray-300 dark:text-gray-600 text-4xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhuma nota fiscal encontrada</p>
                    <a href="{{ route('notas.create') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors">
                        <i class="fas fa-plus mr-2"></i>Criar primeira nota
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Dados dos gráficos
    const statusData = @json($distribuicaoStatus);
    const monthlyData = @json($estatisticasMensais);

    // Verificar tema escuro
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#e5e7eb' : '#374151';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    // Gráfico de Status (Donut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(status => {
                const statusMap = {
                    'rascunho': 'Rascunho',
                    'assinada': 'Assinada',
                    'autorizada': 'Autorizada',
                    'cancelada': 'Cancelada',
                    'rejeitada': 'Rejeitada'
                };
                return statusMap[status] || status;
            }),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#F59E0B', // Amarelo para rascunho
                    '#3B82F6', // Azul para assinada
                    '#10B981', // Verde para autorizada
                    '#EF4444', // Vermelho para cancelada
                    '#6B7280'  // Cinza para rejeitada
                ],
                borderWidth: 2,
                borderColor: isDarkMode ? '#1f2937' : '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textColor,
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Gráfico de Tendência Mensal (Line)
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.mes),
            datasets: [{
                label: 'Notas Criadas',
                data: monthlyData.map(item => item.total),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                },
                y: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Atualizar gráficos quando o tema mudar
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    // Recriar gráficos com nova cor
                    setTimeout(() => location.reload(), 100);
                }
            });
        });
        
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
</script>
@endsection