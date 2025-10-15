<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotaFiscalRejeitada extends Notification
{
    use Queueable;

    private $notaFiscal;
    private $motivo;

    public function __construct($notaFiscal, $motivo = null)
    {
        $this->notaFiscal = $notaFiscal;
        $this->motivo = $motivo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nota Fiscal Rejeitada',
            'message' => "A nota fiscal #{$this->notaFiscal->numero} foi rejeitada pela SEFAZ." . 
                        ($this->motivo ? " Motivo: {$this->motivo}" : ""),
            'icon' => 'fas fa-times-circle',
            'color' => 'red',
            'url' => route('notas.show', $this->notaFiscal->id),
            'nota_fiscal_id' => $this->notaFiscal->id,
            'tipo' => 'rejeicao'
        ];
    }
}