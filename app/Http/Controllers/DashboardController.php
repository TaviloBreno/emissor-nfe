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
            'notas_aprovadas' => $dashboardData['status_count']['aprovada'],
            'notas_pendentes' => $dashboardData['status_count']['pendente'],
            'notas_canceladas' => $dashboardData['status_count']['cancelada'],
            'notas_rejeitadas' => $dashboardData['status_count']['rejeitada'],
        ];
        
        $notasRecentes = $dashboardData['recent_activities'];
        $estatisticasMensais = $dashboardData['monthly_stats'];
        $distribuicaoStatus = $dashboardData['status_count'];
            
        return view('dashboard', compact('stats', 'notasRecentes', 'estatisticasMensais', 'distribuicaoStatus'));
    }
}
