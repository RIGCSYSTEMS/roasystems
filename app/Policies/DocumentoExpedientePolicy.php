<?php

namespace App\Policies;

use App\Models\DocumentoExpediente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentoExpedientePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Ajusta según tus necesidades
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentoExpediente $documento): bool
    {
        if (in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
            return true;
        }

        // Asumiendo que el expediente tiene una relación con el cliente
        return $user->role === 'CLIENTE' && $documento->expediente->client_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentoExpediente $documento): bool
    {
        return in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentoExpediente $documento): bool
    {
        return in_array($user->role, ['ADMIN', 'DIRECTOR']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocumentoExpediente $documento): bool
    {
        return in_array($user->role, ['ADMIN', 'DIRECTOR']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocumentoExpediente $documento): bool
    {
        return in_array($user->role, ['ADMIN']);
    }

    /**
     * Determine whether the user can view the document.
     */
    public function verDocumento(User $user, DocumentoExpediente $documento): bool
    {
        if (in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
            return true;
        }

        return $user->role === 'CLIENTE' && $documento->expediente->client_id === $user->id;
    }
}