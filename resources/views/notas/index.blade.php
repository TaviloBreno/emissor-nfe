@extends('layouts.app')

@section('title', 'Lista de Notas Fiscais - Emissor NFe')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Lista de Notas Fiscais
                </h1>
                <p class="text-gray-600 mt-1">Gerencie suas notas fiscais eletrônicas</p>
            </div>
            <a href="{{ route('notas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nova Nota Fiscal
            </a>
        </div>
    </div>

    <!-- Filtros e Estatísticas -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">Total</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notas->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-green-600 rounded-lg">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600">Autorizadas</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notas->where('status', 'autorizada')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-600 rounded-lg">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600">Rascunho</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notas->where('status', 'rascunho')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-red-600 rounded-lg">
                        <i class="fas fa-times-circle text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-600">Canceladas</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $notas->where('status', 'cancelada')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Notas -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($notas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Número
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data Emissão
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Protocolo
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($notas as $nota)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-invoice text-blue-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ $nota->numero }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $nota->data_emissao->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $nota->tipo === 'saida' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        <i class="fas {{ $nota->tipo === 'saida' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                        {{ ucfirst($nota->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    R$ {{ number_format($nota->valor_total, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'rascunho' => ['color' => 'yellow', 'icon' => 'fa-edit'],
                                            'assinada' => ['color' => 'indigo', 'icon' => 'fa-signature'],
                                            'autorizada' => ['color' => 'green', 'icon' => 'fa-check-circle'],
                                            'cancelada' => ['color' => 'red', 'icon' => 'fa-times-circle'],
                                            'rejeitada' => ['color' => 'red', 'icon' => 'fa-exclamation-triangle']
                                        ];
                                        $config = $statusConfig[$nota->status] ?? ['color' => 'gray', 'icon' => 'fa-question'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                        <i class="fas {{ $config['icon'] }} mr-1"></i>
                                        {{ ucfirst($nota->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $nota->numero_protocolo ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('notas.show', $nota->id) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </a>
                                        
                                        @if($nota->status === 'autorizada')
                                            <a href="{{ route('notas.xml', $nota->id) }}" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                                                <i class="fas fa-download mr-1"></i>
                                                XML
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($notas->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $notas->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-file-invoice text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma nota fiscal encontrada</h3>
                <p class="text-gray-500 mb-6">Comece criando sua primeira nota fiscal eletrônica</p>
                <a href="{{ route('notas.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Criar Primeira Nota
                </a>
            </div>
        @endif
    </div>
</div>
@endsection