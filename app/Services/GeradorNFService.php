<?php

namespace App\Services;

use App\Models\NotaFiscal;

class GeradorNFService
{
    /**
     * Gera XML da Nota Fiscal
     *
     * @param NotaFiscal $notaFiscal
     * @return string
     */
    public function gerarXml(NotaFiscal $notaFiscal): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<notaFiscal>';
        $xml .= '<numero>' . htmlspecialchars($notaFiscal->numero) . '</numero>';
        $xml .= '<dataEmissao>' . $notaFiscal->data_emissao->format('Y-m-d') . '</dataEmissao>';
        $xml .= '<tipo>' . htmlspecialchars($notaFiscal->tipo) . '</tipo>';
        $xml .= '<valorTotal>' . number_format($notaFiscal->valor_total, 2, '.', '') . '</valorTotal>';
        $xml .= '</notaFiscal>';
        
        return $xml;
    }
}