<?php

namespace App\Services;

use App\Models\Inutilizacao;

class InutilizacaoService
{
    /**
     * Inutiliza uma numeração ou faixa de numeração
     *
     * @param array $dados
     * @return array
     */
    public function inutilizarNumeracao(array $dados): array
    {
        try {
            // Verifica se a numeração já foi inutilizada
            if ($this->verificarNumeracaoJaInutilizada($dados)) {
                return [
                    'sucesso' => false,
                    'erro' => 'Numeração já foi inutilizada anteriormente'
                ];
            }

            // Simula comunicação com SEFAZ para inutilização
            $protocolo = $this->gerarProtocoloInutilizacao();

            // Cria registro de inutilização
            $inutilizacao = Inutilizacao::create([
                'serie' => $dados['serie'] ?? '001',
                'numero_inicial' => $dados['numero_inicial'],
                'numero_final' => $dados['numero_final'],
                'justificativa' => $dados['justificativa'],
                'numero_protocolo' => $protocolo,
                'status' => 'autorizada',
                'data_inutilizacao' => now()
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Numeração inutilizada com sucesso',
                'protocolo' => $protocolo,
                'data_inutilizacao' => $inutilizacao->data_inutilizacao->format('Y-m-d H:i:s'),
                'inutilizacao_id' => $inutilizacao->id
            ];

        } catch (\Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro ao inutilizar numeração: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica se a numeração já foi inutilizada
     *
     * @param array $dados
     * @return bool
     */
    private function verificarNumeracaoJaInutilizada(array $dados): bool
    {
        $serie = $dados['serie'] ?? '001';
        $numeroInicial = (int) $dados['numero_inicial'];
        $numeroFinal = (int) $dados['numero_final'];

        // Busca inutilizações que possam conflitar
        $inutilizacoesExistentes = Inutilizacao::where('serie', $serie)
            ->where('status', 'autorizada')
            ->get();

        foreach ($inutilizacoesExistentes as $inutilizacao) {
            $existenteInicial = (int) $inutilizacao->numero_inicial;
            $existenteFinal = (int) $inutilizacao->numero_final;

            // Verifica se há sobreposição de faixas
            if (
                ($numeroInicial >= $existenteInicial && $numeroInicial <= $existenteFinal) ||
                ($numeroFinal >= $existenteInicial && $numeroFinal <= $existenteFinal) ||
                ($numeroInicial <= $existenteInicial && $numeroFinal >= $existenteFinal)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gera protocolo de inutilização
     *
     * @return string
     */
    private function gerarProtocoloInutilizacao(): string
    {
        return 'INUT' . date('Ymd') . sprintf('%06d', rand(100000, 999999));
    }

    /**
     * Consulta inutilizações por série
     *
     * @param string|null $serie
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function consultarInutilizacoes(string $serie = null)
    {
        $query = Inutilizacao::query();
        
        if ($serie) {
            $query->where('serie', $serie);
        }
        
        return $query->orderBy('data_inutilizacao', 'desc')->get();
    }

    /**
     * Verifica se um número específico está inutilizado
     *
     * @param string $numero
     * @param string $serie
     * @return bool
     */
    public function numeroEstaInutilizado(string $numero, string $serie = '001'): bool
    {
        $numeroInt = (int) $numero;

        return Inutilizacao::where('serie', $serie)
            ->where('status', 'autorizada')
            ->where('numero_inicial', '<=', $numeroInt)
            ->where('numero_final', '>=', $numeroInt)
            ->exists();
    }
}