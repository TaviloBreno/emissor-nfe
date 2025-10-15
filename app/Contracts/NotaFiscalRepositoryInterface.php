<?php

namespace App\Contracts;

use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotaFiscalRepositoryInterface
{
    /**
     * Buscar todas as notas fiscais do usuário autenticado com paginação
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllByUser(int $perPage = 10): LengthAwarePaginator;

    /**
     * Buscar nota fiscal por ID (apenas do usuário autenticado)
     *
     * @param int $id
     * @return NotaFiscal|null
     */
    public function findByIdForUser(int $id): ?NotaFiscal;

    /**
     * Criar uma nova nota fiscal
     *
     * @param array $data
     * @return NotaFiscal
     */
    public function create(array $data): NotaFiscal;

    /**
     * Atualizar nota fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @param array $data
     * @return bool
     */
    public function update(NotaFiscal $notaFiscal, array $data): bool;

    /**
     * Excluir nota fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @return bool
     */
    public function delete(NotaFiscal $notaFiscal): bool;

    /**
     * Buscar notas fiscais por status
     *
     * @param string $status
     * @return Collection
     */
    public function findByStatus(string $status): Collection;

    /**
     * Contar notas fiscais por status para o usuário
     *
     * @return array
     */
    public function countByStatus(): array;

    /**
     * Buscar estatísticas mensais do usuário
     *
     * @param int $months
     * @return array
     */
    public function getMonthlyStats(int $months = 6): array;

    /**
     * Buscar atividades recentes do usuário
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentActivities(int $limit = 5): Collection;
}