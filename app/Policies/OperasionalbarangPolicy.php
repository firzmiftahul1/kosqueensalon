<?php

namespace App\Policies;

use App\Models\User;
use App\Models\operasionalbarang;
use Illuminate\Auth\Access\Response;

class OperasionalbarangPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, operasionalbarang $operasionalbarang): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, operasionalbarang $operasionalbarang): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, operasionalbarang $operasionalbarang): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, operasionalbarang $operasionalbarang): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, operasionalbarang $operasionalbarang): bool
    {
        //
        return true;
    }
}
