<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'numero',
        'data_emissao',
        'tipo',
        'valor_total'
    ];
    
    protected $casts = [
        'data_emissao' => 'date',
        'valor_total' => 'decimal:2'
    ];
}
