<?php

namespace App\Services;

use App\Models\NotaFiscal;
use App\Models\EventoNotaFiscal;

class CartaCorrecaoService
{
    /**
     * Emite carta de correção para uma nota fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @param array $dados
     * @return array
     */
    public function emitirCartaCorrecao(NotaFiscal $notaFiscal, array $dados): array
    {
        try {
            // Verifica se a nota pode receber carta de correção
            if ($notaFiscal->status !== 'autorizada') {
                return [
                    'sucesso' => false,
                    'erro' => 'Apenas notas fiscais autorizadas podem receber carta de correção'
                ];
            }

            // Verifica se o campo pode ser corrigido
            if (!$this->campoPermiteCorrecao($dados['campo_corrigido'])) {
                return [
                    'sucesso' => false,
                    'erro' => 'O campo informado não pode ser corrigido via carta de correção'
                ];
            }

            // Gera protocolo do evento
            $protocoloEvento = $this->gerarProtocoloEvento($notaFiscal);

            // Calcula sequência do evento para esta nota
            $sequenciaEvento = $this->obterProximaSequencia($notaFiscal);

            // Cria o evento de correção
            $evento = EventoNotaFiscal::create([
                'nota_fiscal_id' => $notaFiscal->id,
                'tipo_evento' => 'correcao',
                'justificativa' => $dados['justificativa'],
                'dados_anteriores' => [
                    'campo_corrigido' => $dados['campo_corrigido'],
                    'valor' => $dados['valor_anterior']
                ],
                'dados_novos' => [
                    'campo_corrigido' => $dados['campo_corrigido'],
                    'valor' => $dados['valor_novo']
                ],
                'numero_protocolo_evento' => $protocoloEvento,
                'data_evento' => now()
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Carta de correção emitida com sucesso',
                'protocolo_evento' => $protocoloEvento,
                'sequencia_evento' => $sequenciaEvento,
                'data_evento' => $evento->data_evento->format('Y-m-d H:i:s'),
                'evento_id' => $evento->id
            ];

        } catch (\Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro ao emitir carta de correção: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica se o campo permite correção
     *
     * @param string $campo
     * @return bool
     */
    private function campoPermiteCorrecao(string $campo): bool
    {
        $camposPermitidos = [
            'endereco_entrega',
            'dados_adicionais',
            'informacoes_complementares',
            'endereco_retirada',
            'dados_transportador',
            'observacoes'
        ];

        return in_array($campo, $camposPermitidos);
    }

    /**
     * Gera protocolo do evento de correção
     *
     * @param NotaFiscal $notaFiscal
     * @return string
     */
    private function gerarProtocoloEvento(NotaFiscal $notaFiscal): string
    {
        $sequencia = $this->obterProximaSequencia($notaFiscal);
        return 'CCe' . date('Ymd') . sprintf('%06d', $notaFiscal->id) . sprintf('%02d', $sequencia);
    }

    /**
     * Obtém a próxima sequência de evento para a nota
     *
     * @param NotaFiscal $notaFiscal
     * @return int
     */
    private function obterProximaSequencia(NotaFiscal $notaFiscal): int
    {
        $ultimaSequencia = EventoNotaFiscal::where('nota_fiscal_id', $notaFiscal->id)
            ->where('tipo_evento', 'correcao')
            ->count();

        return $ultimaSequencia + 1;
    }

    /**
     * Consulta eventos de correção de uma nota
     *
     * @param NotaFiscal $notaFiscal
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function consultarCorrecoes(NotaFiscal $notaFiscal)
    {
        return EventoNotaFiscal::where('nota_fiscal_id', $notaFiscal->id)
            ->where('tipo_evento', 'correcao')
            ->orderBy('data_evento', 'desc')
            ->get();
    }

    /**
     * Valida se ainda é possível fazer correção na nota
     *
     * @param NotaFiscal $notaFiscal
     * @return array
     */
    public function validarPossibilidadeCorrecao(NotaFiscal $notaFiscal): array
    {
        if ($notaFiscal->status !== 'autorizada') {
            return [
                'pode_corrigir' => false,
                'motivo' => 'Nota não está autorizada'
            ];
        }

        // Limite de correções por nota (normalmente 20)
        $totalCorrecoes = EventoNotaFiscal::where('nota_fiscal_id', $notaFiscal->id)
            ->where('tipo_evento', 'correcao')
            ->count();

        if ($totalCorrecoes >= 20) {
            return [
                'pode_corrigir' => false,
                'motivo' => 'Limite máximo de correções atingido (20)'
            ];
        }

        return [
            'pode_corrigir' => true,
            'correcoes_utilizadas' => $totalCorrecoes,
            'correcoes_restantes' => 20 - $totalCorrecoes
        ];
    }
}