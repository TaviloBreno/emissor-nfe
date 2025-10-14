@extends('layouts.app')

@section('title', 'Nota Fiscal #' . $nota->numero . ' - Emissor NFe')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-file-invoice text-blue-600 mr-2"></i>
                    Detalhes da Nota Fiscal #{{ $nota->numero }}
                </h1>
                <p class="text-gray-600 mt-1">Informações completas da nota fiscal eletrônica</p>
            </div>
            <div class="flex space-x-3">
                @if($nota->status === 'autorizada')
                    <a href="{{ route('notas.xml', $nota->id) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Download XML
                    </a>
                @endif
                <a href="{{ route('notas.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Status e Informações Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Status Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Status da Nota
                </h2>
                
                @php
                    $statusConfig = [
                        'rascunho' => [
                            'color' => 'yellow', 
                            'icon' => 'fa-edit',
                            'text' => 'Rascunho',
                            'description' => 'Nota criada, aguardando processamento'
                        ],
                        'assinada' => [
                            'color' => 'indigo', 
                            'icon' => 'fa-signature',
                            'text' => 'Assinada',
                            'description' => 'Nota assinada digitalmente'
                        ],
                        'autorizada' => [
                            'color' => 'green', 
                            'icon' => 'fa-check-circle',
                            'text' => 'Autorizada',
                            'description' => 'Nota autorizada pela SEFAZ'
                        ],
                        'cancelada' => [
                            'color' => 'red', 
                            'icon' => 'fa-times-circle',
                            'text' => 'Cancelada',
                            'description' => 'Nota cancelada'
                        ],
                        'rejeitada' => [
                            'color' => 'red', 
                            'icon' => 'fa-exclamation-triangle',
                            'text' => 'Rejeitada',
                            'description' => 'Nota rejeitada pela SEFAZ'
                        ]
                    ];
                    $config = $statusConfig[$nota->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => 'Desconhecido', 'description' => ''];
                @endphp
                
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-{{ $config['color'] }}-100 rounded-full mb-4">
                        <i class="fas {{ $config['icon'] }} text-2xl text-{{ $config['color'] }}-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $config['text'] }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $config['description'] }}</p>
                </div>

                @if($nota->numero_protocolo)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Protocolo de Autorização</h4>
                        <p class="text-sm font-mono text-gray-900 break-all">{{ $nota->numero_protocolo }}</p>
                        @if($nota->data_autorizacao)
                            <p class="text-xs text-gray-500 mt-2">
                                Autorizada em: {{ $nota->data_autorizacao->format('d/m/Y H:i:s') }}
                            </p>
                        @endif
                    </div>
                @endif

                @if($nota->codigo_verificacao)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-700 mb-2">Código de Verificação</h4>
                        <p class="text-sm font-mono text-blue-900">{{ $nota->codigo_verificacao }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informações da Nota -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                    Informações da Nota Fiscal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número da Nota</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $nota->numero }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Emissão</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1 text-gray-500"></i>
                                {{ $nota->data_emissao->format('d/m/Y') }}
                                <span class="text-gray-500 ml-2">({{ $nota->data_emissao->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo da Operação</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $nota->tipo === 'saida' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    <i class="fas {{ $nota->tipo === 'saida' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                    {{ ucfirst($nota->tipo) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valor Total</label>
                            <p class="mt-1 text-2xl font-bold text-green-600">
                                R$ {{ number_format($nota->valor_total, 2, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Criação</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1 text-gray-500"></i>
                                {{ $nota->created_at->format('d/m/Y H:i:s') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última Atualização</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-sync mr-1 text-gray-500"></i>
                                {{ $nota->updated_at->format('d/m/Y H:i:s') }}
                                <span class="text-gray-500 ml-2">({{ $nota->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Disponíveis -->
    @if($nota->status === 'autorizada')
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-cogs text-blue-600 mr-2"></i>
                Ações Disponíveis
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="flex items-center justify-center p-4 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition duration-200">
                    <div class="text-center">
                        <i class="fas fa-ban text-red-500 text-2xl mb-2"></i>
                        <h3 class="text-sm font-medium text-gray-900">Cancelar Nota</h3>
                        <p class="text-xs text-gray-500">Cancelar a nota fiscal</p>
                    </div>
                </button>

                <button class="flex items-center justify-center p-4 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition duration-200">
                    <div class="text-center">
                        <i class="fas fa-edit text-yellow-500 text-2xl mb-2"></i>
                        <h3 class="text-sm font-medium text-gray-900">Carta de Correção</h3>
                        <p class="text-xs text-gray-500">Emitir carta de correção</p>
                    </div>
                </button>

                <button class="flex items-center justify-center p-4 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition duration-200">
                    <div class="text-center">
                        <i class="fas fa-thumbs-up text-green-500 text-2xl mb-2"></i>
                        <h3 class="text-sm font-medium text-gray-900">Manifestação</h3>
                        <p class="text-xs text-gray-500">Registrar manifestação</p>
                    </div>
                </button>
            </div>
        </div>
    @endif

    <!-- Histórico/Eventos -->
    @if($nota->eventos && $nota->eventos->count() > 0)
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                Histórico de Eventos
            </h2>
            
            <div class="space-y-4">
                @foreach($nota->eventos->sortByDesc('created_at') as $evento)
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            @php
                                $eventoIcons = [
                                    'cancelamento' => ['icon' => 'fa-ban', 'color' => 'red'],
                                    'correcao' => ['icon' => 'fa-edit', 'color' => 'yellow'],
                                    'inutilizacao' => ['icon' => 'fa-times', 'color' => 'gray'],
                                    'manifestacao_ciencia' => ['icon' => 'fa-eye', 'color' => 'blue'],
                                    'manifestacao_confirmacao' => ['icon' => 'fa-check', 'color' => 'green'],
                                    'manifestacao_discordancia' => ['icon' => 'fa-times-circle', 'color' => 'red']
                                ];
                                $eventoConfig = $eventoIcons[$evento->tipo_evento] ?? ['icon' => 'fa-info', 'color' => 'gray'];
                            @endphp
                            <div class="flex items-center justify-center w-8 h-8 bg-{{ $eventoConfig['color'] }}-100 rounded-full">
                                <i class="fas {{ $eventoConfig['icon'] }} text-{{ $eventoConfig['color'] }}-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ ucwords(str_replace('_', ' ', $evento->tipo_evento)) }}
                            </h4>
                            @if($evento->justificativa)
                                <p class="text-sm text-gray-600 mt-1">{{ $evento->justificativa }}</p>
                            @endif
                            @if($evento->protocolo)
                                <p class="text-xs text-gray-500 mt-1 font-mono">Protocolo: {{ $evento->protocolo }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $evento->created_at->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Informações Técnicas -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-info-circle text-gray-600 mr-2"></i>
            Informações Técnicas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600">ID da Nota:</span>
                <span class="text-gray-900 font-mono">#{{ $nota->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Modelo:</span>
                <span class="text-gray-900">55 (NFe)</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Série:</span>
                <span class="text-gray-900">1</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Ambiente:</span>
                <span class="text-gray-900">{{ config('app.env') === 'production' ? 'Produção' : 'Homologação' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection