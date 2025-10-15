<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotaFiscalService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $notaFiscalService;

    public function __construct(NotaFiscalService $notaFiscalService)
    {
        $this->notaFiscalService = $notaFiscalService;
    }
    /**
     * Exibe o dashboard do usuário autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obter todas as estatísticas através do service
        $dashboardData = $this->notaFiscalService->getDashboardStats();
        
        // Preparar dados para a view
        $stats = [
            'total_notas' => $dashboardData['total_notas'],
            'notas_aprovadas' => $dashboardData['status_count']['autorizada'] ?? 0,
            'notas_pendentes' => $dashboardData['status_count']['rascunho'] ?? 0,
            'notas_canceladas' => $dashboardData['status_count']['cancelada'] ?? 0,
            'notas_rejeitadas' => $dashboardData['status_count']['rejeitada'] ?? 0,
            'notas_assinadas' => $dashboardData['status_count']['assinada'] ?? 0,
            'valor_total' => $dashboardData['valor_total'] ?? 0,
        ];
        
        $notasRecentes = $dashboardData['recent_activities'];
        $estatisticasMensais = $dashboardData['monthly_stats'];
        $distribuicaoStatus = $dashboardData['status_count'];
            
        return view('dashboard', compact('stats', 'notasRecentes', 'estatisticasMensais', 'distribuicaoStatus'));
    }
}
