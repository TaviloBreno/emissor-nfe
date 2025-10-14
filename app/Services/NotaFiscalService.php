<?php

namespace App\Services;

use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Collection;

class NotaFiscalService
{
    protected $geradorNFService;
    protected $assinadorService;
    protected $nfeClient;

    public function __construct()
    {
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
     * Cancela uma nota fiscal autorizada
     *
     * @param NotaFiscal $notaFiscal
     * @param string $justificativa
     * @return array
     */
    public function cancelarNota(NotaFiscal $notaFiscal, string $justificativa): array
    {
        if ($notaFiscal->status !== 'autorizada') {
            return [
                'sucesso' => false,
                'erro' => 'Apenas notas autorizadas podem ser canceladas'
            ];
        }

        // Aqui seria feita a comunicação com SEFAZ para cancelamento
        // Por simplicidade, apenas atualizamos o status
        $notaFiscal->update(['status' => 'cancelada']);

        return [
            'sucesso' => true,
            'mensagem' => 'Nota fiscal cancelada com sucesso'
        ];
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
}