@extends('layouts.dashboard')

@section('title', 'Relatórios - Emissor NFe')

@section('page-title', 'Relatórios')
@section('page-description', 'Análise de desempenho e estatísticas do sistema')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período</label>
                        <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option>Últimos 30 dias</option>
                            <option>Últimos 90 dias</option>
                            <option>Últimos 6 meses</option>
                            <option>Último ano</option>
                            <option>Personalizado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option>Todos</option>
                            <option>Aprovadas</option>
                            <option>Pendentes</option>
                            <option>Rejeitadas</option>
                            <option>Canceladas</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="btn-secondary">
                        <i class="fas fa-filter mr-2"></i>Filtrar
                    </button>
                    <button class="btn-primary" onclick="exportarRelatorio()">
                        <i class="fas fa-download mr-2"></i>Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas Resumo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Notas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total de Notas</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($totalNotas) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>+12% este mês
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Faturamento Total -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Faturamento Total</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>+8% este mês
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Taxa de Aprovação -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Taxa de Aprovação</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $totalNotas > 0 ? number_format(($notasAprovadas / $totalNotas) * 100, 1) : 0 }}%</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>+2% este mês
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

            <!-- Ticket Médio -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Ticket Médio</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">R$ {{ $totalNotas > 0 ? number_format($faturamentoTotal / $totalNotas, 2, ',', '.') : '0,00' }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            <i class="fas fa-arrow-down text-red-500 mr-1"></i>-3% este mês
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calculator text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Notas por Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Notas por Status</h3>
                <div class="relative h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Faturamento por Mês -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Faturamento Mensal</h3>
                <div class="relative h-64">
                    <canvas id="faturamentoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabela de Resumo -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo Detalhado</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Percentual</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Aprovadas</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($notasAprovadas) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $totalNotas > 0 ? number_format(($notasAprovadas / $totalNotas) * 100, 1) : 0 }}%</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">R$ {{ number_format($faturamentoTotal * 0.7, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Pendentes</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($notasPendentes) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $totalNotas > 0 ? number_format(($notasPendentes / $totalNotas) * 100, 1) : 0 }}%</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">R$ {{ number_format($faturamentoTotal * 0.2, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Rejeitadas</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($notasRejeitadas) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $totalNotas > 0 ? number_format(($notasRejeitadas / $totalNotas) * 100, 1) : 0 }}%</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">R$ {{ number_format($faturamentoTotal * 0.1, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exportação -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exportar Relatório</h3>
        </div>
        <form action="{{ route('relatorios.exportar') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formato</label>
                    <select name="formato" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período</label>
                    <select name="periodo" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="mensal">Mensal</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="fecharExportModal()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Exportar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Status
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Aprovadas', 'Pendentes', 'Rejeitadas', 'Canceladas'],
        datasets: [{
            data: [{{ $notasAprovadas }}, {{ $notasPendentes }}, {{ $notasRejeitadas }}, {{ $notasCanceladas }}],
            backgroundColor: [
                '#10B981',
                '#F59E0B', 
                '#EF4444',
                '#6B7280'
            ],
            borderWidth: 2,
            borderColor: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151',
                    padding: 20
                }
            }
        }
    }
});

// Gráfico de Faturamento
const faturamentoCtx = document.getElementById('faturamentoChart').getContext('2d');
const faturamentoChart = new Chart(faturamentoCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($faturamentoPorMes, 'mes')) !!},
        datasets: [{
            label: 'Faturamento',
            data: {!! json_encode(array_column($faturamentoPorMes, 'valor')) !!},
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                },
                grid: {
                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                }
            },
            y: {
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280',
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                },
                grid: {
                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                }
            }
        }
    }
});

function exportarRelatorio() {
    document.getElementById('exportModal').classList.remove('hidden');
    document.getElementById('exportModal').classList.add('flex');
}

function fecharExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
    document.getElementById('exportModal').classList.remove('flex');
}

// Fechar modal ao clicar fora
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharExportModal();
    }
});
</script>
@endsection