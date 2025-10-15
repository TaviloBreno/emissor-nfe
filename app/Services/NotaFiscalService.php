<?php

namespace App\Services;

use App\Contracts\NotaFiscalRepositoryInterface;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class NotaFiscalService
{
    /**
     * @var NotaFiscalRepositoryInterface
     */
    protected $repository;
    protected $geradorNFService;
    protected $assinadorService;
    protected $nfeClient;

    public function __construct(NotaFiscalRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->geradorNFService = new GeradorNFService();
        $this->assinadorService = new AssinadorService();
        $this->nfeClient = new NFeClient();
    }

    /**
     * Processa o envio completo da nota fiscal:
     * 1. Gera XML
     * 2. Assina digitalmente
     * 3. Envia para SEFAZ
     * 4. Atualiza status e protocolo
     *
     * @param NotaFiscal $notaFiscal
     * @return array
     */
    public function processarEnvioCompleto(NotaFiscal $notaFiscal): array
    {
        try {
            // Passo 1: Gerar XML da nota fiscal
            $xml = $this->geradorNFService->gerarXml($notaFiscal);

            // Passo 2: Assinar o XML
            $xmlAssinado = $this->assinarNota($notaFiscal, $xml);

            // Passo 3: Enviar para SEFAZ
            $respostaSefaz = $this->nfeClient->enviarParaSefaz($xmlAssinado);

            if ($respostaSefaz['status'] === 'sucesso') {
                // Passo 4: Atualizar nota com protocolo e status autorizada
                $this->autorizarNota($notaFiscal, $respostaSefaz);

                return [
                    'sucesso' => true,
                    'protocolo' => $respostaSefaz['protocolo'],
                    'mensagem' => 'Nota fiscal processada e autorizada com sucesso',
                    'data_autorizacao' => $respostaSefaz['data_autorizacao']
                ];
            } else {
                // Em caso de erro, volta status para rascunho
                $this->voltarStatusRascunho($notaFiscal);
                
                return [
                    'sucesso' => false,
                    'erro' => $respostaSefaz['mensagem']
                ];
            }

        } catch (\Exception $e) {
            // Em caso de exceção, volta status para rascunho
            $this->voltarStatusRascunho($notaFiscal);
            
            return [
                'sucesso' => false,
                'erro' => 'Erro interno: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Assina uma nota fiscal e atualiza seu status
     *
     * @param NotaFiscal $notaFiscal
     * @param string|null $xml
     * @return string
     */
    public function assinarNota(NotaFiscal $notaFiscal, string $xml = null): string
    {
        // Se não foi fornecido XML, gera um novo
        if ($xml === null) {
            $xml = $this->geradorNFService->gerarXml($notaFiscal);
        }

        // Assina o XML
        $xmlAssinado = $this->assinadorService->assinarXml($xml);

        // Atualiza status para assinada
        $notaFiscal->update(['status' => 'assinada']);

        return $xmlAssinado;
    }

    /**
     * Autoriza a nota fiscal salvando protocolo e atualizando status
     *
     * @param NotaFiscal $notaFiscal
     * @param array $respostaSefaz
     * @return void
     */
    public function autorizarNota(NotaFiscal $notaFiscal, array $respostaSefaz): void
    {
        $notaFiscal->update([
            'status' => 'autorizada',
            'numero_protocolo' => $respostaSefaz['protocolo'],
            'data_autorizacao' => $respostaSefaz['data_autorizacao'],
            'codigo_verificacao' => $respostaSefaz['codigo_verificacao'] ?? null
        ]);
    }

    /**
     * Volta o status da nota para rascunho
     *
     * @param NotaFiscal $notaFiscal
     * @return void
     */
    public function voltarStatusRascunho(NotaFiscal $notaFiscal): void
    {
        $notaFiscal->update(['status' => 'rascunho']);
    }

    /**
     * Consulta notas fiscais por status
     *
     * @param string $status
     * @return Collection
     */
    public function consultarPorStatus(string $status): Collection
    {
        return NotaFiscal::where('status', $status)->get();
    }

    /**
     * Cancela uma nota fiscal autorizada (método antigo - manter compatibilidade)
     *
     * @param NotaFiscal $notaFiscal
     * @param string $justificativa
     * @return array
     */
    public function cancelarNota(NotaFiscal $notaFiscal, string $justificativa): array
    {
        return $this->cancelarNotaFiscal($notaFiscal, $justificativa);
    }

    /**
     * Cancela uma nota fiscal com validação de prazo e registro de evento
     *
     * @param NotaFiscal $notaFiscal
     * @param string $justificativa
     * @return array
     */
    public function cancelarNotaFiscal(NotaFiscal $notaFiscal, string $justificativa): array
    {
        // Verifica se a nota pode ser cancelada
        if ($notaFiscal->status !== 'autorizada') {
            return [
                'sucesso' => false,
                'erro' => 'Apenas notas fiscais autorizadas podem ser canceladas'
            ];
        }

        // Verifica o prazo de 24 horas para cancelamento
        if ($notaFiscal->data_autorizacao && $notaFiscal->data_autorizacao->diffInHours(now()) > 24) {
            return [
                'sucesso' => false,
                'erro' => 'Prazo de cancelamento expirado. Notas podem ser canceladas apenas em até 24 horas após a autorização.'
            ];
        }

        try {
            // Atualiza o status da nota
            $notaFiscal->update(['status' => 'cancelada']);

            // Registra o evento de cancelamento
            \App\Models\EventoNotaFiscal::create([
                'nota_fiscal_id' => $notaFiscal->id,
                'tipo_evento' => 'cancelamento',
                'justificativa' => $justificativa,
                'numero_protocolo_evento' => 'CANC' . time(),
                'data_evento' => now()
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Nota fiscal cancelada com sucesso'
            ];

        } catch (\Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro ao cancelar nota fiscal: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Consulta o status atual de uma nota na SEFAZ
     *
     * @param NotaFiscal $notaFiscal
     * @return array
     */
    public function consultarStatusSefaz(NotaFiscal $notaFiscal): array
    {
        if (!$notaFiscal->numero_protocolo) {
            return [
                'status' => 'não_enviada',
                'mensagem' => 'Nota fiscal ainda não foi enviada para SEFAZ'
            ];
        }

        // Simulação de consulta de status na SEFAZ
        return [
            'status' => 'autorizada',
            'protocolo' => $notaFiscal->numero_protocolo,
            'data_autorizacao' => $notaFiscal->data_autorizacao->format('Y-m-d H:i:s')
        ];
    }

    // ========== NOVOS MÉTODOS USANDO REPOSITORY PATTERN ==========

    /**
     * Listar todas as notas fiscais do usuário
     */
    public function getAllNotas(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAllByUser($perPage);
    }

    /**
     * Buscar nota fiscal por ID
     */
    public function findNota(int $id): ?NotaFiscal
    {
        return $this->repository->findByIdForUser($id);
    }

    /**
     * Criar nova nota fiscal com validação de negócio
     */
    public function createNota(array $data): NotaFiscal
    {
        // Validações de negócio específicas
        $this->validateNotaData($data);

        // Calcular valor total se não informado
        if (!isset($data['valor_total'])) {
            $data['valor_total'] = $this->calculateTotal($data);
        }

        // Status padrão
        if (!isset($data['status'])) {
            $data['status'] = 'pendente';
        }

        return $this->repository->create($data);
    }

    /**
     * Atualizar nota fiscal
     */
    public function updateNota(NotaFiscal $nota, array $data): bool
    {
        // Verificar se pode ser editada
        if (!$this->canEdit($nota)) {
            throw ValidationException::withMessages([
                'status' => 'Esta nota fiscal não pode ser editada no status atual.'
            ]);
        }

        $this->validateNotaData($data);

        // Recalcular valor total se necessário
        if (isset($data['quantidade']) || isset($data['valor_unitario'])) {
            $data['valor_total'] = $this->calculateTotal($data, $nota);
        }

        return $this->repository->update($nota, $data);
    }

    /**
     * Excluir nota fiscal
     */
    public function deleteNota(NotaFiscal $nota): bool
    {
        if (!$this->canDelete($nota)) {
            throw ValidationException::withMessages([
                'status' => 'Esta nota fiscal não pode ser excluída no status atual.'
            ]);
        }

        return $this->repository->delete($nota);
    }

    /**
     * Buscar notas por status
     */
    public function getNotasByStatus(string $status): Collection
    {
        return $this->repository->findByStatus($status);
    }

    /**
     * Obter estatísticas do dashboard
     */
    public function getDashboardStats(): array
    {
        $statusCount = $this->repository->countByStatus();
        $monthlyStats = $this->repository->getMonthlyStats();
        $recentActivities = $this->repository->getRecentActivities();
        
        // Calcular valor total das notas autorizadas
        $valorTotal = $this->repository->getModel()
            ->where('user_id', Auth::id())
            ->where('status', 'autorizada')
            ->sum('valor_total');

        return [
            'total_notas' => array_sum($statusCount),
            'status_count' => $statusCount,
            'monthly_stats' => $monthlyStats,
            'recent_activities' => $recentActivities,
            'valor_total' => $valorTotal,
        ];
    }

    /**
     * Aprovar nota fiscal
     */
    public function approveNota(NotaFiscal $nota): bool
    {
        if ($nota->status !== 'pendente') {
            throw ValidationException::withMessages([
                'status' => 'Apenas notas pendentes podem ser aprovadas.'
            ]);
        }

        return $this->repository->update($nota, ['status' => 'aprovada']);
    }

    /**
     * Cancelar nota fiscal
     */
    public function cancelNota(NotaFiscal $nota, string $motivo = null): bool
    {
        if (!in_array($nota->status, ['pendente', 'aprovada'])) {
            throw ValidationException::withMessages([
                'status' => 'Esta nota não pode ser cancelada no status atual.'
            ]);
        }

        $data = ['status' => 'cancelada'];
        if ($motivo) {
            $data['observacoes'] = ($nota->observacoes ? $nota->observacoes . "\n\n" : '') . 
                                  "Cancelamento: " . $motivo;
        }

        return $this->repository->update($nota, $data);
    }

    // ========== MÉTODOS PRIVADOS DE VALIDAÇÃO ==========

    /**
     * Validar dados da nota fiscal
     */
    private function validateNotaData(array $data): void
    {
        // Validações de negócio específicas aqui
        if (isset($data['quantidade']) && $data['quantidade'] <= 0) {
            throw ValidationException::withMessages([
                'quantidade' => 'A quantidade deve ser maior que zero.'
            ]);
        }

        if (isset($data['valor_unitario']) && $data['valor_unitario'] <= 0) {
            throw ValidationException::withMessages([
                'valor_unitario' => 'O valor unitário deve ser maior que zero.'
            ]);
        }
    }

    /**
     * Calcular valor total
     */
    private function calculateTotal(array $data, NotaFiscal $nota = null): float
    {
        $quantidade = $data['quantidade'] ?? ($nota->quantidade ?? 1);
        $valorUnitario = $data['valor_unitario'] ?? ($nota->valor_unitario ?? 0);

        return $quantidade * $valorUnitario;
    }

    /**
     * Verificar se a nota pode ser editada
     */
    private function canEdit(NotaFiscal $nota): bool
    {
        return in_array($nota->status, ['pendente', 'rejeitada']);
    }

    /**
     * Verificar se a nota pode ser excluída
     */
    private function canDelete(NotaFiscal $nota): bool
    {
        return in_array($nota->status, ['pendente', 'rejeitada', 'cancelada']);
    }
}