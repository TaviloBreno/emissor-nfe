<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaFiscal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard do usuário autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->id();
        
        // Estatísticas gerais
        $stats = [
            'total_notas' => NotaFiscal::where('user_id', $userId)->count(),
            'notas_autorizadas' => NotaFiscal::where('user_id', $userId)->where('status', 'autorizada')->count(),
            'notas_rascunho' => NotaFiscal::where('user_id', $userId)->where('status', 'rascunho')->count(),
            'notas_canceladas' => NotaFiscal::where('user_id', $userId)->where('status', 'cancelada')->count(),
            'valor_total' => NotaFiscal::where('user_id', $userId)->where('status', 'autorizada')->sum('valor_total'),
        ];
        
        // Notas recentes (últimas 5)
        $notasRecentes = NotaFiscal::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Estatísticas mensais (últimos 6 meses)
        $estatisticasMensais = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $inicioMes = $mes->copy()->startOfMonth();
            $fimMes = $mes->copy()->endOfMonth();
            
            $estatisticasMensais[] = [
                'mes' => $mes->format('M/Y'),
                'total_notas' => NotaFiscal::where('user_id', $userId)
                    ->whereBetween('created_at', [$inicioMes, $fimMes])
                    ->count(),
                'valor_total' => NotaFiscal::where('user_id', $userId)
                    ->where('status', 'autorizada')
                    ->whereBetween('created_at', [$inicioMes, $fimMes])
                    ->sum('valor_total'),
            ];
        }
        
        // Distribuição por status
        $distribuicaoStatus = NotaFiscal::where('user_id', $userId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        return view('dashboard', compact('stats', 'notasRecentes', 'estatisticasMensais', 'distribuicaoStatus'));
    }
}
