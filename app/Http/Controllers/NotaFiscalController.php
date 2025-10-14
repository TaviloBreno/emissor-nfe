<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotaFiscalRequest;
use App\Http\Requests\CancelamentoNotaRequest;
use App\Http\Requests\InutilizacaoRequest;
use App\Http\Requests\CartaCorrecaoRequest;
use App\Http\Requests\ManifestacaoRequest;
use App\Models\NotaFiscal;
use App\Services\NotaFiscalService;
use App\Services\InutilizacaoService;
use App\Services\CartaCorrecaoService;
use App\Services\ManifestacaoService;
use Illuminate\Http\Request;

class NotaFiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NotaFiscalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotaFiscalRequest $request)
    {
        $notaFiscal = NotaFiscal::create($request->validated());
        
        return response()->json($notaFiscal, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Cancela uma nota fiscal autorizada
     *
     * @param  \App\Http\Requests\CancelamentoNotaRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelar(CancelamentoNotaRequest $request, $id)
    {
        $notaFiscal = NotaFiscal::findOrFail($id);
        $notaFiscalService = new NotaFiscalService();
        
        $resultado = $notaFiscalService->cancelarNotaFiscal($notaFiscal, $request->validated()['justificativa']);
        
        if ($resultado['sucesso']) {
            return response()->json([
                'success' => true,
                'message' => $resultado['mensagem']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $resultado['erro']
            ], 422);
        }
    }

    /**
     * Inutiliza numeração de notas fiscais
     *
     * @param  \App\Http\Requests\InutilizacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function inutilizar(InutilizacaoRequest $request)
    {
        $inutilizacaoService = new InutilizacaoService();
        $resultado = $inutilizacaoService->inutilizarNumeracao($request->validated());
        
        if ($resultado['sucesso']) {
            return response()->json([
                'success' => true,
                'message' => $resultado['mensagem'],
                'protocolo' => $resultado['protocolo'],
                'data_inutilizacao' => $resultado['data_inutilizacao']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $resultado['erro']
            ], 422);
        }
    }

    /**
     * Consulta inutilizações
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function consultarInutilizacoes(Request $request)
    {
        $inutilizacaoService = new InutilizacaoService();
        $serie = $request->get('serie');
        $inutilizacoes = $inutilizacaoService->consultarInutilizacoes($serie);
        
        return response()->json([
            'data' => $inutilizacoes
        ]);
    }

    /**
     * Emite carta de correção para uma nota fiscal
     *
     * @param  \App\Http\Requests\CartaCorrecaoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function emitirCartaCorrecao(CartaCorrecaoRequest $request, $id)
    {
        $notaFiscal = NotaFiscal::findOrFail($id);
        $cartaCorrecaoService = new CartaCorrecaoService();
        
        $resultado = $cartaCorrecaoService->emitirCartaCorrecao($notaFiscal, $request->validated());
        
        if ($resultado['sucesso']) {
            return response()->json([
                'success' => true,
                'message' => $resultado['mensagem'],
                'protocolo_evento' => $resultado['protocolo_evento'],
                'sequencia_evento' => $resultado['sequencia_evento'],
                'data_evento' => $resultado['data_evento']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $resultado['erro']
            ], 422);
        }
    }

    /**
     * Registra manifestação do destinatário
     *
     * @param  \App\Http\Requests\ManifestacaoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manifestar(ManifestacaoRequest $request, $id)
    {
        try {
            $notaFiscal = NotaFiscal::findOrFail($id);
            
            $manifestacaoService = new ManifestacaoService();
            
            $resultado = $manifestacaoService->registrarManifestacao(
                $notaFiscal,
                $request->input('tipo_manifestacao'),
                $request->input('justificativa')
            );

            return response()->json([
                'success' => true,
                'message' => 'Manifestação registrada com sucesso',
                'data' => $resultado
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
