<?php

namespace App\Policies;

use App\Models\NotaFiscal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotaFiscalPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode visualizar a lista de notas
     */
    public function viewAny(User $user)
    {
        return true; // usuário logado pode ver suas próprias notas
    }

    /**
     * Determina se o usuário pode visualizar uma nota específica
     */
    public function view(User $user, NotaFiscal $notaFiscal)
    {
        // Debug para verificar os IDs
        logger('Policy check - User ID: ' . $user->id . ', Nota user_id: ' . $notaFiscal->user_id);
        
        return $user->id === $notaFiscal->user_id;
    }

    /**
     * Determina se o usuário pode criar notas
     */
    public function create(User $user)
    {
        return true; // usuário logado pode criar notas
    }

    /**
     * Determina se o usuário pode atualizar uma nota
     */
    public function update(User $user, NotaFiscal $notaFiscal)
    {
        return $user->id === $notaFiscal->user_id;
    }

    /**
     * Determina se o usuário pode deletar uma nota
     */
    public function delete(User $user, NotaFiscal $notaFiscal)
    {
        return $user->id === $notaFiscal->user_id;
    }

    /**
     * Determina se o usuário pode fazer download do XML
     */
    public function download(User $user, NotaFiscal $notaFiscal)
    {
        return $user->id === $notaFiscal->user_id;
    }
}