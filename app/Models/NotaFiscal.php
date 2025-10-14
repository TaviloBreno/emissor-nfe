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
        'valor_total',
        'numero_protocolo',
        'status',
        'data_autorizacao',
        'codigo_verificacao',
        'user_id'
    ];    protected $casts = [
        'data_emissao' => 'date',
        'data_autorizacao' => 'datetime',
        'valor_total' => 'decimal:2'
    ];

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com eventos da nota fiscal
     */
    public function eventos()
    {
        return $this->hasMany(EventoNotaFiscal::class);
    }
}
