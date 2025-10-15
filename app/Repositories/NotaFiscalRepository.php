<?php

namespace App\Repositories;

use App\Contracts\NotaFiscalRepositoryInterface;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotaFiscalRepository implements NotaFiscalRepositoryInterface
{
    /**
     * @var NotaFiscal
     */
    protected $model;

    public function __construct(NotaFiscal $model)
    {
        $this->model = $model;
    }

    /**
     * Get the model instance
     */
    public function getModel(): NotaFiscal
    {
        return $this->model;
    }

    /**
     * Buscar todas as notas fiscais do usuário autenticado com paginação
     */
    public function getAllByUser(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar nota fiscal por ID (apenas do usuário autenticado)
     */
    public function findByIdForUser(int $id): ?NotaFiscal
    {
        return $this->model
            ->where('user_id', Auth::id())
            ->find($id);
    }

    /**
     * Criar uma nova nota fiscal
     */
    public function create(array $data): NotaFiscal
    {
        $data['user_id'] = Auth::id();
        return $this->model->create($data);
    }

    /**
     * Atualizar nota fiscal
     */
    public function update(NotaFiscal $notaFiscal, array $data): bool
    {
        return $notaFiscal->update($data);
    }

    /**
     * Excluir nota fiscal
     */
    public function delete(NotaFiscal $notaFiscal): bool
    {
        return $notaFiscal->delete();
    }

    /**
     * Buscar notas fiscais por status
     */
    public function findByStatus(string $status): Collection
    {
        return $this->model
            ->where('user_id', Auth::id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Contar notas fiscais por status para o usuário
     */
    public function countByStatus(): array
    {
        $counts = $this->model
            ->where('user_id', Auth::id())
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'rascunho' => $counts['rascunho'] ?? 0,
            'assinada' => $counts['assinada'] ?? 0,
            'autorizada' => $counts['autorizada'] ?? 0,
            'cancelada' => $counts['cancelada'] ?? 0,
            'rejeitada' => $counts['rejeitada'] ?? 0,
        ];
    }

    /**
     * Buscar estatísticas mensais do usuário
     */
    public function getMonthlyStats(int $months = 6): array
    {
        $startDate = Carbon::now()->subMonths($months);
        
        $monthlyData = $this->model
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "aprovada" THEN 1 ELSE 0 END) as aprovadas'),
                DB::raw('SUM(valor_total) as valor_total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            $data = $monthlyData->where('year', $year)->where('month', $month)->first();
            
            $result[] = [
                'mes' => $date->format('M/y'),
                'total' => $data->total ?? 0,
                'aprovadas' => $data->aprovadas ?? 0,
                'valor_total' => $data->valor_total ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Buscar atividades recentes do usuário
     */
    public function getRecentActivities(int $limit = 5): Collection
    {
        return $this->model
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }
}