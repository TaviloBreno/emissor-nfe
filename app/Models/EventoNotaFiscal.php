<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoNotaFiscal extends Model
{
    use HasFactory;
    
    protected $table = 'eventos_nota_fiscal';
    
    protected $fillable = [
        'nota_fiscal_id',
        'tipo_evento',
        'justificativa',
        'dados_anteriores',
        'dados_novos',
        'numero_protocolo_evento',
        'data_evento'
    ];
    
    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
        'data_evento' => 'datetime'
    ];
    
    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class);
    }
}
