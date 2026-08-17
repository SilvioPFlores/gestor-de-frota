<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
//use Illuminate\Auth\Access\Response;

class TripPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viagens.visualizar');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Trip $trip): bool
    {
        if (! $user->can('viagens.visualizar')) {
            return false;
        }

        if ($user->hasRole('Solicitante')) {
            return $trip->user_id === $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Trip $trip): bool
    {
        if (! $user->can('viagens.editar')) {
            return false;
        }

        // Gestor e Admin podem editar qualquer viagem.
        if (! $user->hasRole('Solicitante')) {
            return true;
        }

        // Solicitante só pode editar a própria viagem
        // enquanto ela estiver como "Solicitada".
        return $trip->user_id === $user->id
            && $trip->status === 'Solicitada';
    }

    public function updateVehicle(User $user, Trip $trip): bool
    {
        return $user->can('viagens.alterar_veiculo');
    }

    public function updateDriver(User $user, Trip $trip): bool
    {
        return $user->can('viagens.alterar_motorista');
    }

    public function updateStatus(User $user, Trip $trip): bool
    {
        // Gestor e Admin possuem a permissão normalmente.
        if ($user->can('viagens.alterar_status')) {

            // Motorista não pode alterar uma viagem ainda solicitada.
            if ($user->hasRole('Motorista') && $trip->status === 'Solicitada') {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Trip $trip): bool
    {
        if (! $user->can('viagens.cancelar')) {
            return false;
        }

        // Admin e Gestor podem cancelar qualquer viagem.
        if (! $user->hasRole('Solicitante')) {
            return true;
        }

        // Solicitante só pode cancelar a própria viagem.
        return $trip->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Trip $trip): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Trip $trip): bool
    {
        return false;
    }
}
