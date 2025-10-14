@extends('layouts.app')

@section('title', 'Nova Nota Fiscal - Emissor NFe')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-plus text-green-600 mr-2"></i>
                    Nova Nota Fiscal
                </h1>
                <p class="text-gray-600 mt-1">Preencha os dados para criar uma nova nota fiscal eletrônica</p>
            </div>
            <a href="{{ route('notas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Formulário -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('notas.store') }}" x-data="notaForm()" @submit="validateForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Número da Nota -->
                <div>
                    <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hashtag text-blue-500 mr-1"></i>
                        Número da Nota *
                    </label>
                    <input type="text" 
                           name="numero" 
                           id="numero"
                           value="{{ old('numero') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('numero') border-red-500 @enderror"
                           placeholder="Ex: 001234"
                           x-model="form.numero"
                           required>
                    @error('numero')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Número único da nota fiscal (máx. 20 caracteres)
                    </p>
                </div>

                <!-- Data de Emissão -->
                <div>
                    <label for="data_emissao" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-blue-500 mr-1"></i>
                        Data de Emissão *
                    </label>
                    <input type="date" 
                           name="data_emissao" 
                           id="data_emissao"
                           value="{{ old('data_emissao', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('data_emissao') border-red-500 @enderror"
                           x-model="form.data_emissao"
                           required>
                    @error('data_emissao')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exchange-alt text-blue-500 mr-1"></i>
                        Tipo *
                    </label>
                    <select name="tipo" 
                            id="tipo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror"
                            x-model="form.tipo"
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>
                            <i class="fas fa-arrow-down"></i> Entrada
                        </option>
                        <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>
                            <i class="fas fa-arrow-up"></i> Saída
                        </option>
                    </select>
                    @error('tipo')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Valor Total -->
                <div>
                    <label for="valor_total" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-blue-500 mr-1"></i>
                        Valor Total *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor_total" 
                               id="valor_total"
                               value="{{ old('valor_total') }}"
                               step="0.01"
                               min="0.01"
                               max="999999.99"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor_total') border-red-500 @enderror"
                               placeholder="0,00"
                               x-model="form.valor_total"
                               @input="formatCurrency"
                               required>
                    </div>
                    @error('valor_total')
                        <p class="mt-1 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Valor entre R$ 0,01 e R$ 999.999,99
                    </p>
                </div>
            </div>

            <!-- Preview da Nota -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg" x-show="isFormValid()" x-cloak>
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-eye text-blue-500 mr-2"></i>
                    Preview da Nota Fiscal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-600">Número:</span>
                        <span class="text-gray-900" x-text="form.numero || '-'"></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Data:</span>
                        <span class="text-gray-900" x-text="formatDate(form.data_emissao)"></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Tipo:</span>
                        <span class="text-gray-900" x-text="form.tipo ? form.tipo.charAt(0).toUpperCase() + form.tipo.slice(1) : '-'"></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Valor:</span>
                        <span class="text-gray-900 font-semibold" x-text="formatValue(form.valor_total)"></span>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('notas.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!isFormValid()">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Nota
                </button>
            </div>
        </form>
    </div>

    <!-- Informações de Ajuda -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Informações Importantes
        </h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                A nota será criada com status "Rascunho" e poderá ser processada posteriormente
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                O número da nota deve ser único no sistema
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                Após salvar, você poderá visualizar e processar a nota
            </li>
        </ul>
    </div>
</div>

<script>
function notaForm() {
    return {
        form: {
            numero: '',
            data_emissao: '{{ date("Y-m-d") }}',
            tipo: '',
            valor_total: ''
        },
        
        isFormValid() {
            return this.form.numero && this.form.data_emissao && this.form.tipo && this.form.valor_total;
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        },
        
        formatValue(value) {
            if (!value) return 'R$ 0,00';
            return 'R$ ' + parseFloat(value).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },
        
        validateForm(event) {
            if (!this.isFormValid()) {
                event.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        }
    }
}
</script>
@endsection