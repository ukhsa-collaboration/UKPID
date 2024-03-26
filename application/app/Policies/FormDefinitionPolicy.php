<?php

namespace App\Policies;

use App\Models\FormDefinition;
use App\Models\User;

class FormDefinitionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormDefinition $formDefinition): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('form_data.update');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormDefinition $formDefinition): bool
    {
        return $user->can('form_data.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormDefinition $formDefinition): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormDefinition $formDefinition): bool
    {
        return $user->can('form_data.update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormDefinition $formDefinition): bool
    {
        return false;
    }
}
