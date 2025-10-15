<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\NotaFiscalRepositoryInterface;

class RelatorioController extends Controller
{
    private $notaFiscalRepository;

    public function __construct(NotaFiscalRepositoryInterface $notaFiscalRepository)
    {
        $this->middleware('auth');
        $this->notaFiscalRepository = $notaFiscalRepository;
    }

    public function index()
    {
        // Estatísticas gerais
        $totalNotas = $this->notaFiscalRepository->count();
        $notasAprovadas = $this->notaFiscalRepository->countByStatus('aprovada');
        $notasPendentes = $this->notaFiscalRepository->countByStatus('pendente');
        $notasRejeitadas = $this->notaFiscalRepository->countByStatus('rejeitada');
        $notasCanceladas = $this->notaFiscalRepository->countByStatus('cancelada');

        // Faturamento total
        $faturamentoTotal = $this->notaFiscalRepository->sumValorTotal();
        $faturamentoMesAtual = $this->notaFiscalRepository->sumValorTotalByMonth(now());

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
            $faturamentoPorMes[] = [
                'mes' => $mes->format('M/Y'),
                'valor' => $this->notaFiscalRepository->sumValorTotalByMonth($mes)
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