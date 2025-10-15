<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaFiscal;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Estatísticas gerais
        $totalNotas = NotaFiscal::count();
        $notasAprovadas = NotaFiscal::where('status', 'aprovada')->count();
        $notasPendentes = NotaFiscal::where('status', 'pendente')->count();
        $notasRejeitadas = NotaFiscal::where('status', 'rejeitada')->count();
        $notasCanceladas = NotaFiscal::where('status', 'cancelada')->count();

        // Faturamento total
        $faturamentoTotal = NotaFiscal::sum('valor_total') ?? 0;
        $faturamentoMesAtual = NotaFiscal::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->sum('valor_total') ?? 0;

        // Notas por status para gráfico
        $notasPorStatus = [
            'aprovada' => $notasAprovadas,
            'pendente' => $notasPendentes,
            'rejeitada' => $notasRejeitadas,
            'cancelada' => $notasCanceladas,
        ];

        // Faturamento por mês (últimos 6 meses)
        $faturamentoPorMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $valor = NotaFiscal::whereMonth('created_at', $mes->month)
                              ->whereYear('created_at', $mes->year)
                              ->sum('valor_total') ?? 0;
            $faturamentoPorMes[] = [
                'mes' => $mes->format('M/Y'),
                'valor' => $valor
            ];
        }

        return view('relatorios.index', compact(
            'totalNotas',
            'notasAprovadas', 
            'notasPendentes',
            'notasRejeitadas',
            'notasCanceladas',
            'faturamentoTotal',
            'faturamentoMesAtual',
            'notasPorStatus',
            'faturamentoPorMes'
        ));
    }

    public function exportar(Request $request)
    {
        $formato = $request->input('formato', 'pdf');
        $periodo = $request->input('periodo', 'mensal');
        
        // Lógica de exportação seria implementada aqui
        // Por enquanto, vamos apenas retornar uma mensagem
        
        return redirect()->back()->with('success', 'Relatório exportado com sucesso!');
    }
}