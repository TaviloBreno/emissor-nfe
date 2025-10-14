<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inutilizacao extends Model
{
    use HasFactory;
    
    protected $table = 'inutilizacoes';
    
    protected $fillable = [
        'serie',
        'numero_inicial',
        'numero_final',
        'justificativa',
        'numero_protocolo',
        'status',
        'data_inutilizacao'
    ];
    
    protected $casts = [
        'data_inutilizacao' => 'datetime'
    ];
}
