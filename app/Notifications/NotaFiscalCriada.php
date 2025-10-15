<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotaFiscalCriada extends Notification
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
            'title' => 'Nova Nota Fiscal',
            'message' => "A nota fiscal #{$this->notaFiscal->numero} foi criada e está pendente de envio.",
            'icon' => 'fas fa-file-plus',
            'color' => 'blue',
            'url' => route('notas.show', $this->notaFiscal->id),
            'nota_fiscal_id' => $this->notaFiscal->id,
            'tipo' => 'criacao'
        ];
    }
}