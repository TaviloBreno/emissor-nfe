<?php

namespace App\Services;

use App\Models\NotaFiscal;
use App\Models\EventoNotaFiscal;

class ManifestacaoService
{
    /**
     * Registra manifestação do destinatário para uma nota fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @param string $tipoManifestacao
     * @param string $justificativa
     * @return array
     * @throws \Exception
     */
    public function registrarManifestacao(NotaFiscal $notaFiscal, string $tipoManifestacao, string $justificativa): array
    {
        // Valida se a nota pode receber manifestação
        $this->validarNotaParaManifestacao($notaFiscal);

        // Mapeia o tipo de manifestação para o tipo de evento
        $tipoEvento = $this->mapearTipoManifestacao($tipoManifestacao);

        // Gera protocolo único para a manifestação
        $protocolo = $this->gerarProtocoloManifestacao($notaFiscal, $tipoManifestacao);

        // Simula envio para SEFAZ
        $this->enviarManifestacaoSefaz($notaFiscal, $tipoManifestacao, $justificativa, $protocolo);

        // Registra o evento no banco de dados
        $evento = $this->criarEventoManifestacao(
            $notaFiscal,
            $tipoEvento,
            $justificativa,
            $protocolo
        );

        return [
            'id' => $evento->id,
            'tipo_manifestacao' => $tipoManifestacao,
            'protocolo' => $protocolo,
            'data_manifestacao' => $evento->created_at->format('Y-m-d H:i:s'),
            'justificativa' => $justificativa
        ];
    }

    /**
     * Valida se a nota fiscal pode receber manifestação
     *
     * @param NotaFiscal $notaFiscal
     * @throws \Exception
     */
    private function validarNotaParaManifestacao(NotaFiscal $notaFiscal): void
    {
        if ($notaFiscal->status !== 'autorizada') {
            throw new \Exception('Só é possível manifestar sobre notas fiscais autorizadas');
        }

        if (empty($notaFiscal->numero_protocolo)) {
            throw new \Exception('Nota fiscal deve possuir protocolo de autorização');
        }
    }

    /**
     * Mapeia o tipo de manifestação para o tipo de evento
     *
     * @param string $tipoManifestacao
     * @return string
     */
    private function mapearTipoManifestacao(string $tipoManifestacao): string
    {
        $mapeamento = [
            'ciencia' => 'manifestacao_ciencia',
            'confirmacao' => 'manifestacao_confirmacao',
            'discordancia' => 'manifestacao_discordancia'
        ];

        return $mapeamento[$tipoManifestacao];
    }

    /**
     * Gera protocolo único para a manifestação
     *
     * @param NotaFiscal $notaFiscal
     * @param string $tipoManifestacao
     * @return string
     */
    private function gerarProtocoloManifestacao(NotaFiscal $notaFiscal, string $tipoManifestacao): string
    {
        $prefixos = [
            'ciencia' => '210',
            'confirmacao' => '220',
            'discordancia' => '230'
        ];

        $prefixo = $prefixos[$tipoManifestacao] ?? '200';
        $timestamp = now()->format('YmdHis');
        $sufixo = str_pad($notaFiscal->id, 6, '0', STR_PAD_LEFT);

        return $prefixo . $timestamp . $sufixo;
    }

    /**
     * Simula envio da manifestação para SEFAZ
     *
     * @param NotaFiscal $notaFiscal
     * @param string $tipoManifestacao
     * @param string $justificativa
     * @param string $protocolo
     * @return bool
     */
    private function enviarManifestacaoSefaz(
        NotaFiscal $notaFiscal,
        string $tipoManifestacao,
        string $justificativa,
        string $protocolo
    ): bool {
        // Simula comunicação com SEFAZ
        // Em produção aqui seria feita a comunicação real com o webservice da SEFAZ

        // Log da operação simulada
        logger()->info("Manifestação enviada para SEFAZ", [
            'numero' => $notaFiscal->numero,
            'tipo_manifestacao' => $tipoManifestacao,
            'protocolo' => $protocolo,
            'justificativa' => $justificativa
        ]);

        // Simula sucesso sempre (em produção trataria retorno da SEFAZ)
        return true;
    }

    /**
     * Cria registro do evento de manifestação
     *
     * @param NotaFiscal $notaFiscal
     * @param string $tipoEvento
     * @param string $justificativa
     * @param string $protocolo
     * @return EventoNotaFiscal
     */
    private function criarEventoManifestacao(
        NotaFiscal $notaFiscal,
        string $tipoEvento,
        string $justificativa,
        string $protocolo
    ): EventoNotaFiscal {
        return EventoNotaFiscal::create([
            'nota_fiscal_id' => $notaFiscal->id,
            'tipo_evento' => $tipoEvento,
            'protocolo' => $protocolo,
            'justificativa' => $justificativa
        ]);
    }

    /**
     * Lista todas as manifestações de uma nota fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listarManifestacoes(NotaFiscal $notaFiscal)
    {
        return $notaFiscal->eventos()
            ->whereIn('tipo_evento', [
                'manifestacao_ciencia',
                'manifestacao_confirmacao', 
                'manifestacao_discordancia'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Verifica se uma nota já possui determinado tipo de manifestação
     *
     * @param NotaFiscal $notaFiscal
     * @param string $tipoManifestacao
     * @return bool
     */
    public function possuiManifestacao(NotaFiscal $notaFiscal, string $tipoManifestacao): bool
    {
        $tipoEvento = $this->mapearTipoManifestacao($tipoManifestacao);
        
        return $notaFiscal->eventos()
            ->where('tipo_evento', $tipoEvento)
            ->exists();
    }
}