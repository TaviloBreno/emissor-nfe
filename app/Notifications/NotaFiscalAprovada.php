<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotaFiscalAprovada extends Notification
{
    use Queueable;

    private $notaFiscal;

    public function __construct($notaFiscal)
    {
        $this->notaFiscal = $notaFiscal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nota Fiscal Aprovada',
            'message' => "A nota fiscal #{$this->notaFiscal->numero} foi aprovada pela SEFAZ.",
            'icon' => 'fas fa-check-circle',
            'color' => 'green',
            'url' => route('notas.show', $this->notaFiscal->id),
            'nota_fiscal_id' => $this->notaFiscal->id,
            'tipo' => 'aprovacao'
        ];
    }
}