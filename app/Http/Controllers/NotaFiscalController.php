<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotaFiscalRequest;
use App\Http\Requests\NotaFiscalWebRequest;
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
    public function __construct()
    {
        $this->middleware('auth')->except(['storeApi']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', NotaFiscal::class);
        
        // Filtra apenas as notas do usuário logado
        $notas = NotaFiscal::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('notas.index', compact('notas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', NotaFiscal::class);
        
        return view('notas.create');
    }

    /**
     * Store a newly created resource in storage via API.
     *
     * @param  \App\Http\Requests\NotaFiscalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeApi(NotaFiscalRequest $request)
    {
        $notaFiscal = NotaFiscal::create($request->validated());
        
        return response()->json($notaFiscal, 201);
    }

    /**
     * Store a newly created resource in storage via web form.
     *
     * @param  \App\Http\Requests\NotaFiscalWebRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotaFiscalWebRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            
            $notaFiscal = NotaFiscal::create($data);
            
            return redirect()->route('notas.index')
                ->with('success', 'Nota fiscal criada com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao criar nota fiscal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nota = NotaFiscal::findOrFail($id);
        
        return view('notas.show', compact('nota'));
    }

    /**
     * Download XML da nota fiscal.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadXml($id)
    {
        $nota = NotaFiscal::findOrFail($id);
        
        if ($nota->status !== 'autorizada') {
            return redirect()->back()
                ->with('error', 'XML disponível apenas para notas autorizadas.');
        }

        // Gera XML simulado para download
        $xml = $this->gerarXmlParaDownload($nota);
        
        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="NFe_' . $nota->numero . '.xml"'
        ]);
    }

    /**
     * Gera XML simulado para download
     */
    private function gerarXmlParaDownload(NotaFiscal $nota): string
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<NFe xmlns=\"http://www.portalfiscal.inf.br/nfe\">
    <infNFe Id=\"NFe{$nota->numero}\">
        <ide>
            <nNF>{$nota->numero}</nNF>
            <dhEmi>{$nota->data_emissao->format('Y-m-d\\TH:i:s')}</dhEmi>
            <tpNF>" . ($nota->tipo === 'saida' ? '1' : '0') . "</tpNF>
        </ide>
        <det>
            <prod>
                <vProd>{$nota->valor_total}</vProd>
            </prod>
        </det>
        <total>
            <vNF>{$nota->valor_total}</vNF>
        </total>
    </infNFe>
    <protNFe>
        <infProt>
            <nProt>{$nota->numero_protocolo}</nProt>
            <dhRecbto>{$nota->created_at->format('Y-m-d\\TH:i:s')}</dhRecbto>
        </infProt>
    </protNFe>
</NFe>";
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

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nota fiscal não encontrada'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
