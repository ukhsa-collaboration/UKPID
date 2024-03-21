<?php

namespace App\Policies;

use App\Models\CodeTable;
use App\Models\User;

class CodeTablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('code_table.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CodeTable $codeTable): bool
    {
        return $user->can('code_table.read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('code_table.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CodeTable $codeTable): bool
    {
        return $user->can('code_table.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CodeTable $codeTable): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CodeTable $codeTable): bool
    {
        return $user->can('code_table.update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CodeTable $codeTable): bool
    {
        return false;
    }
}
